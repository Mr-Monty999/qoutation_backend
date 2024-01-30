<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\User\StoreServiceRequest;
use App\Http\Requests\Api\User\UpdateServiceRequest;
use App\Models\City;
use App\Models\Country;
use App\Models\Neighbourhood;
use App\Models\Service;
use App\Models\ServiceProduct;
use App\Services\ServiceProductService;
use App\Services\ServiceService;
use Dflydev\DotAccessData\Data;
use Illuminate\Http\Request;
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
        $services = Service::with(
            "user.supplier",
            "user.buyer",
            "activities",
            "userQuotation",
            "city",
            "country",
            "neighbourhood"
        )
            ->withCount("serviceQuotations")
            ->where("status", "active")
            ->latest()->paginate(10);

        return response()->json($services);
    }
    public function markAsCompleted(Request $request, Service $service)
    {
        if ($service->status == "completed")
            return response()->json([], 403);


        $service->update([
            "status" => "completed"
        ]);

        $service->load(
            "activities",
            "user.buyer",
            "city",
            "country",
            "neighbourhood"
        );
        return response()->json([
            "data" => $service
        ]);
    }
    public function buyerServices()
    {
        $user = Auth::user();
        $services = $user->services()
            ->with(
                "user.supplier",
                "user.buyer",
                "activities",
                "userQuotation",
                "city",
                "country",
                "neighbourhood"
            )
            ->withCount("serviceQuotations")
            ->latest()->paginate(10);

        return response()->json($services);
    }

    public function supplierServices(Request $request)
    {
        $user = Auth::user();
        $userActivities = $user->activities->pluck("id");
        $services = Service::with([
            "user.supplier", "user.buyer",
            "activities",
            "userQuotation",
            "city",
            "country",
            "neighbourhood"
        ])
            ->withCount("serviceQuotations");




        if ($request->type == "sent") {
            $services->whereHas("serviceQuotations", function ($q) use ($user) {
                $q->where("user_id", $user->id);
            });
        } else {
            $services->whereHas("activities", function ($q) use ($userActivities) {
                $q->whereIn("activity_id", $userActivities);
            });
            $services->where("status", "active");
        }


        $services = $services->latest()->paginate(10);

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



        $country = Country::findOrFail($request->country_id);
        $city = City::findOrFail($request->city_id);
        $neighbourhood = Neighbourhood::findOrFail($request->neighbourhood_id);

        if ($city->country_id != $country->id || $neighbourhood->city_id != $city->id)
            return response()->json([], 403);

        $user = auth()->user();

        if (!$user->buyer)
            return response()->json([], 403);

        DB::beginTransaction();
        try {

            // $request->merge(["activity_ids" => explode(",", $request->activity_ids)]);


            $data = $request->validated();
            $data["user_id"] = $user->id;

            $serviceId = Service::create($data)->id;

            $service = Service::find($serviceId);

            ServiceProductService::store($data["products"], $service);


            $service->activities()->attach($request->activity_ids);

            $service->load(
                "activities",
                "user.buyer",
                "user.supplier",
                "city",
                "country",
                "neighbourhood"
            );
            $service->loadCount("serviceQuotations");


            DB::commit();
            return response()->json($service, 201);
        } catch (\Exception $e) {
            DB::rollback(); // If an error occurs, rollback the transaction
            return response()->json(["msg" => $e->__toString()], 400);
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
            return response()->json([], 403);

        $service->load([
            "user.buyer",
            "user.supplier",
            "activities",
            "city",
            "country",
            "neighbourhood"
        ])
            ->loadCount("serviceQuotations");

        $service["service_quotations"] = $service->serviceQuotations()->with("user.supplier", "acceptedBy")->orderBy("amount")
            ->paginate(10);




        return response()->json($service);
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


        $country = Country::findOrFail($request->country_id);
        $city = City::findOrFail($request->city_id);
        $neighbourhood = Neighbourhood::findOrFail($request->neighbourhood_id);

        if ($city->country_id != $country->id || $neighbourhood->city_id != $city->id)
            return response()->json([], 403);

        $user = auth()->user();

        if (!$user->buyer || $service->user_id != $user->id)
            return response()->json([], 403);

        if ($service->status != "active")
            return response()->json([], 403);

        DB::beginTransaction();
        try {

            // $request->merge(["activity_ids" => explode(",", $request->activity_ids)]);


            $data = $request->validated();

            // $service->update($data);


            // $service->activities()->sync($request->activity_ids);

            $service->load(
                "activities",
                "user.buyer",
                "user.supplier",
                "city",
                "country",
                "neighbourhood"
            );
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
        if ($service->status != "active")
            return response()->json([], 403);

        $service->delete();


        return response()->json([], 204);
    }
}
