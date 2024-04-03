<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Jobs\EmailJob;
use App\Models\City;
use App\Models\Country;
use App\Models\Neighbourhood;
use App\Models\Service;
use App\Notifications\ServiceCompleteNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ServiceController extends Controller
{
    public function index()
    {
        $services = Service::with(
            "user.supplier",
            "user.buyer",
            "activities",
            "city",
            "country",
            "neighbourhood",
            "authUserReply"
        )
            ->where("status", "active")
            ->latest()->paginate(10);

        return response()->json($services);
    }
    public function updateStatus(Request $request, Service $service)
    {
        $user = auth()->user();

        if ($request->status != "complete")
            return response()->json([], 403);

        if ($service->status == "completed")
            return response()->json([], 403);


        if ($service->user_id != $user->id)
            return response()->json([], 403);


        $service->update([
            "status" => $request->status
        ]);

        if ($request->status == "complete") {
            $serviceReplies = $service->replies()->with("user")->get();
            $serviceOwner = $service->user;

            foreach ($serviceReplies as $reply) {
                $reply->user->notify(new ServiceCompleteNotification([
                    "service_id" => $service->id,
                    "service_reply_id" => $reply->id,
                    "sender_id" => $user->id,
                    "messages" => [
                        "ar" => trans("messages.service_complete", [
                            "name" => $user->name
                        ], "ar"),
                        "en" => trans("messages.service_complete", [
                            "name" => $user->name
                        ], "en")
                    ]
                ]));

                EmailJob::dispatch([
                    "type" => "service_complete",
                    "target_email" => $reply->user->email,
                    "buyer_name" => $serviceOwner->name,
                    "buyer_phone" => $serviceOwner->phone->country_code . $serviceOwner->phone->number,
                    "buyer_email" => $serviceOwner->email,
                    "service_reply_title" => $service->title,
                    "service_reply_price" => $service->price,
                    "service_reply_description" => $service->description,
                    "service_id" => $service->id,
                    "service_reply_id" => $reply->id,
                ]);
            }
        }

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
                "city",
                "country",
                "neighbourhood"
            )
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
            "city",
            "country",
            "neighbourhood",
            'authUserReply'
        ]);



        if ($request->type == "sent") {
            $services->whereHas("replies", function ($q) use ($user) {
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

    public function store(Request $request)
    {


        $data = $this->validate($request, [
            "title" => "required|string",
            "description" => "required|string",
            "activity_ids" => "required|array",
            "country_id" => "required|numeric|exists:countries,id",
            "city_id" => "required|numeric|exists:cities,id",
            "neighbourhood_id" => "required|numeric|exists:neighbourhoods,id",
        ], [
            "country_id" => trans("messages.country")
        ]);


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

            $data["user_id"] = $user->id;

            $serviceId = Service::create($data)->id;

            $service = Service::find($serviceId);

            $service->activities()->attach($request->activity_ids);

            $service->load(
                "activities",
                "user.buyer",
                "user.supplier",
                "city",
                "country",
                "neighbourhood"
            );


            DB::commit();
            return response()->json($service, 201);
        } catch (\Exception $e) {
            DB::rollback(); // If an error occurs, rollback the transaction
            return response()->json(["msg" => $e->__toString()], 400);
        }
    }

    public function show(Service $service)
    {

        $user = auth()->user();

        // if ($service->user->id != $user->id)
        //     return response()->json([], 403);

        $service->load([
            "user.buyer",
            "user.supplier",
            "activities",
            "city",
            "country",
            "neighbourhood",
            "replies.user.supplier",
            "replies" => function ($q) {
                $q->orderBy("price");
            }
        ]);


        return response()->json($service);
    }



    public function update(Request $request, Service $service)
    {

        $data = $this->validate($request, [
            "title" => "required|string",
            "description" => "required|string",
            "activity_ids" => "required|array",
            "country_id" => "required|numeric|exists:countries,id",
            "city_id" => "required|numeric|exists:cities,id",
            "neighbourhood_id" => "required|numeric|exists:neighbourhoods,id",
        ], [
            "country_id" => trans("messages.country")
        ]);

        $country = Country::findOrFail($request->country_id);
        $city = City::findOrFail($request->city_id);
        $neighbourhood = Neighbourhood::findOrFail($request->neighbourhood_id);

        if ($city->country_id != $country->id || $neighbourhood->city_id != $city->id)
            return response()->json([], 403);

        $user = auth()->user();
        $data["user_id"] = $user->id;

        if (!$user->buyer || $service->user_id != $user->id)
            return response()->json([], 403);

        if ($service->status != "active")
            return response()->json([], 403);

        DB::beginTransaction();
        try {

            $service->update($data);


            $service->activities()->sync($request->activity_ids);

            $service->load(
                "activities",
                "user.buyer",
                "user.supplier",
                "city",
                "country",
                "neighbourhood"
            );




            DB::commit();
            return response()->json($service, 200);
        } catch (\Exception $e) {
            DB::rollback(); // If an error occurs, rollback the transaction
            return response()->json(["msg" => "error"], 400);
        }
    }

    public function destroy(Service $service)
    {

        $user = auth()->user();

        if ($service->user_id != $user->id)
            return response()->json([], 403);

        if ($service->status != "active")
            return response()->json([], 403);

        $service->delete();


        return response()->json([], 204);
    }
}
