<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\User\StoreQuotationRequest;
use App\Http\Requests\Api\User\UpdateQuotationRequest;
use App\Models\City;
use App\Models\Country;
use App\Models\Neighbourhood;
use App\Models\Quotation;
use App\Models\QuotationProduct;
use App\Services\QuotationProductService;
use App\Services\ServiceService;
use Dflydev\DotAccessData\Data;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class QuotationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $quotations = Quotation::with(
            "user.supplier",
            "user.buyer",
            "activities",
            "city",
            "country",
            "userReplyInvoice",
            "neighbourhood"
        )
            ->where("status", "active")
            ->latest()->paginate(10);

        return response()->json($quotations);
    }
    public function markAsCompleted(Request $request, Quotation $quotation)
    {
        $user = auth()->user();


        if ($quotation->status == "completed")
            return response()->json([], 403);


        if ($quotation->user_id != $user->id)
            return response()->json([], 403);


        $quotation->update([
            "status" => "completed"
        ]);

        $quotation->load(
            "activities",
            "user.buyer",
            "city",
            "country",
            "neighbourhood"
        );
        return response()->json([
            "data" => $quotation
        ]);
    }
    public function buyerQuotations()
    {
        $user = Auth::user();
        $quotations = $user->quotations()
            ->with(
                "user.supplier",
                "user.buyer",
                "activities",
                "city",
                "country",
                "userReplyInvoice",
                "neighbourhood"
            )
            ->latest()->paginate(10);

        return response()->json($quotations);
    }

    public function supplierQuotations(Request $request)
    {
        $user = Auth::user();
        $userActivities = $user->activities->pluck("id");
        $quotations = Quotation::with([
            "user.supplier", "user.buyer",
            "activities",
            "city",
            "country",
            "userReplyInvoice",
            "neighbourhood"
        ]);



        if ($request->type == "sent") {
            $quotations->whereHas("replies", function ($q) use ($user) {
                $q->where("user_id", $user->id);
            });
        } else {
            $quotations->whereHas("activities", function ($q) use ($userActivities) {
                $q->whereIn("activity_id", $userActivities);
            });
            $quotations->where("status", "active");
        }


        $quotations = $quotations->latest()->paginate(10);

        return response()->json($quotations);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreQuotationRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreQuotationRequest $request)
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

            $data = $request->validated();
            $data["user_id"] = $user->id;

            $quotationId = Quotation::create($data)->id;

            $quotation = Quotation::find($quotationId);

            QuotationProductService::store($data["products"], $quotation);


            $quotation->activities()->attach($request->activity_ids);

            $quotation->load(
                "activities",
                "user.buyer",
                "user.supplier",
                "city",
                "country",
                "neighbourhood"
            );


            DB::commit();
            return response()->json($quotation, 201);
        } catch (\Exception $e) {
            DB::rollback(); // If an error occurs, rollback the transaction
            return response()->json(["msg" => $e->__toString()], 400);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Quotation  $quotation
     * @return \Illuminate\Http\Response
     */
    public function show(Quotation $quotation)
    {

        $user = auth()->user();


        $quotation->load([
            "user.buyer",
            "user.supplier",
            "activities",
            "city",
            "country",
            "neighbourhood",
            "userReplyInvoice",
            "authUserReplyProducts",
            "products" => function ($q) {
                $q->withCount("replies");
            },
            "products.acceptedReply.user.phone",
            "products.acceptedReply.user.supplier",
            "products.replies.user.supplier",
            "products.replies.user.phone",
            "products.replies.invoice",
            "products.replies" => function ($q) {
                $q->orderBy("unit_price");
            }
        ]);





        return response()->json($quotation);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateQuotationRequest  $request
     * @param  \App\Models\Quotation  $quotation
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateQuotationRequest $request, Quotation $quotation)
    {


        $country = Country::findOrFail($request->country_id);
        $city = City::findOrFail($request->city_id);
        $neighbourhood = Neighbourhood::findOrFail($request->neighbourhood_id);

        if ($city->country_id != $country->id || $neighbourhood->city_id != $city->id)
            return response()->json([], 403);

        $user = auth()->user();

        if (!$user->buyer || $quotation->user_id != $user->id)
            return response()->json([], 403);

        if ($quotation->status != "active")
            return response()->json([], 403);

        DB::beginTransaction();
        try {

            $data = $request->validated();

            // $quotation->update($data);


            // $quotation->activities()->sync($request->activity_ids);

            $quotation->load(
                "activities",
                "user.buyer",
                "user.supplier",
                "city",
                "country",
                "userReplyInvoice",
                "neighbourhood"
            );




            DB::commit();
            return response()->json($quotation, 200);
        } catch (\Exception $e) {
            DB::rollback(); // If an error occurs, rollback the transaction
            return response()->json(["msg" => "error"], 400);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Quotation  $quotation
     * @return \Illuminate\Http\Response
     */
    public function destroy(Quotation $quotation)
    {

        $user = auth()->user();

        if ($quotation->user_id != $user->id)
            return response()->json([], 403);

        if ($quotation->status != "active")
            return response()->json([], 403);

        $quotation->delete();


        return response()->json([], 204);
    }
}
