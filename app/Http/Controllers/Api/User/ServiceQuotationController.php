<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\User\StoreServiceQuotationRequest;
use App\Http\Requests\Api\User\UpdateServiceQuotationRequest;
use App\Models\Service;
use App\Models\ServiceQuotation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ServiceQuotationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function allUserQuotations(Request $request)
    {
        $user = auth()->user();

        $quotations = ServiceQuotation::with("user", "service");

        $quotations->where("user_id", $user->id);


        $quotations =  $quotations->get();

        return response()->json([
            "data" => $quotations
        ]);
    }
    public function index(Request $request, $serviceId)
    {

        $user = auth()->user();

        $quotations = ServiceQuotation::with("user", "service");

        $quotations->where("service_id", $serviceId);

        $quotations =  $quotations->get();

        return response()->json([
            "data" => $quotations
        ]);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreServiceQuotationRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreServiceQuotationRequest $request, $serviceId)
    {

        $user = auth()->user();
        $userWallet = $user->wallet;

        if (!$user->supplier)
            abort(403);

        if (!$userWallet || $userWallet->balance < env("SUPPLIER_QUOTATION_PRICE"))
            return response()->json([
                "message" => trans("messages.you dont have enough money in your wallet !")
            ], 403);

        $quotationExists = ServiceQuotation::where("service_id", $serviceId)
            ->where("user_id", $user->id)
            ->exists();

        if ($quotationExists)
            abort(403);

        DB::beginTransaction();
        try {


            $data = $request->validated();
            $data["user_id"] = $user->id;
            $data["service_id"] = $serviceId;
            $quotation = ServiceQuotation::create($data);
            $quotation->load("user", "service");
            $userWallet->balance -= env('SUPPLIER_QUOTATION_PRICE');
            $userWallet->save();


            DB::commit();
            return response()->json([
                "data" => $quotation
            ], 201);
        } catch (\Exception $e) {
            DB::rollback(); // If an error occurs, rollback the transaction
            return response()->json(["msg" => $e->__toString()], 400);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ServiceQuotation  $serviceQuotation
     * @return \Illuminate\Http\Response
     */
    public function show($serviceId, $quotationId)
    {
        $serviceQuotation = ServiceQuotation::findOrFail($quotationId);

        $serviceQuotation->load("user", "service");

        return response()->json($serviceQuotation);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ServiceQuotation  $serviceQuotation
     * @return \Illuminate\Http\Response
     */

    public function update(UpdateServiceQuotationRequest $request, $serviceId, $quotationId)
    {

        $serviceQuotation = ServiceQuotation::findOrFail($quotationId);
        $user = auth()->user();

        if (!$user->supplier || $serviceQuotation->user_id != $user->id)
            abort(403);


        DB::beginTransaction();
        try {


            $data = $request->validated();
            $serviceQuotation->update($data);
            $serviceQuotation->load("user", "service");

            DB::commit();
            return response()->json([
                "data" => $serviceQuotation
            ], 200);
        } catch (\Exception $e) {
            DB::rollback(); // If an error occurs, rollback the transaction
            return response()->json(["msg" => "error"], 400);
        }
    }

    public function destroy($serviceId, $quotationId)
    {
        $serviceQuotation = ServiceQuotation::findOrFail($quotationId);

        $serviceQuotation->delete();

        return response()->json([], 204);
    }
}
