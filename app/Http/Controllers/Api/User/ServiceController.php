<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\User\StoreServiceRequest;
use App\Http\Requests\Api\User\UpdateServiceRequest;
use App\Models\Service;
use App\Services\ServiceService;
use Dflydev\DotAccessData\Data;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ServiceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $services = Service::with("user", "activities")
            ->withCount("serviceQuotations")
            ->latest()->paginate(10);

        return response()->json($services);
    }
    public function buyerServices()
    {
        $user = Auth::user();
        $services = $user->services()
            ->with("user", "activities")
            ->withCount("serviceQuotations")
            ->latest()->paginate(10);

        return response()->json($services);
    }

    public function supplierServices()
    {
        $user = Auth::user();
        $userActivities = $user->activities->pluck("id");
        $services = Service::with([
            "user",
            "activities",
            "userQuotation"
        ])
            ->whereHas("activities", function ($q) use ($userActivities) {
                $q->whereIn("activity_id", $userActivities);
            })
            ->withCount("serviceQuotations")
            ->latest()->paginate(10);

        return response()->json($services);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreServiceRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreServiceRequest $request)
    {

        $user = auth()->user();

        if (!$user->buyer)
            abort(403);

        DB::beginTransaction();
        try {

            $request->merge(["activity_ids" => explode(",", $request->activity_ids)]);


            $data = $request->validated();
            $data["user_id"] = $user->id;

            $service = ServiceService::store($data);

            $service->activities()->attach($request->activity_ids);

            $service->load("activities", "user");
            $service->loadCount("serviceQuotations");


            DB::commit();
            return response()->json($service, 201);
        } catch (\Exception $e) {
            DB::rollback(); // If an error occurs, rollback the transaction
            return response()->json(["msg" => "error"], 400);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Service  $service
     * @return \Illuminate\Http\Response
     */
    public function show(Service $service)
    {

        $user = auth()->user();

        if ($service->user_id != $user->id)
            abort(403);

        $service->load([
            "user",
            "activities",
        ])
            ->loadCount("serviceQuotations");

        $data = $service;
        $serviceQuotations = $service->serviceQuotations;
        $serviceQuotations->load("user.supplier");
        $data["service_quotations"] = $service->serviceQuotations()->orderBy("amount")
            ->paginate(1);




        return response()->json($data);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateServiceRequest  $request
     * @param  \App\Models\Service  $service
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateServiceRequest $request, Service $service)
    {
        $user = auth()->user();

        if (!$user->buyer || $service->user_id != $user->id)
            abort(403);

        DB::beginTransaction();
        try {

            $request->merge(["activity_ids" => explode(",", $request->activity_ids)]);


            $data = $request->validated();

            $service->update($data);


            $service->activities()->sync($request->activity_ids);

            $service->load("activities", "user");
            $service->loadCount("serviceQuotations");




            DB::commit();
            return response()->json($service, 200);
        } catch (\Exception $e) {
            DB::rollback(); // If an error occurs, rollback the transaction
            return response()->json(["msg" => "error"], 400);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Service  $service
     * @return \Illuminate\Http\Response
     */
    public function destroy(Service $service)
    {
        $service->delete();

        return response()->json([], 204);
    }
}
