<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\User\StoreServiceQuotationRequest;
use App\Http\Requests\Api\User\UpdateServiceQuotationRequest;
use App\Http\Requests\StoreServiceQoutationRequest;
use App\Http\Requests\UpdateServiceQoutationRequest;
use App\Models\ServiceQoutation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ServiceQoutationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        $user = auth()->user();

        $quotations = ServiceQoutation::with("user", "service");

        if ($request->type == "own") {
            $quotations->where("user_id", $user->id);
        }
        $quotations =  $quotations->get();

        return response()->json([
            "data" => $quotations
        ]);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreServiceQoutationRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreServiceQuotationRequest $request)
    {

        $user = auth()->user();
        $userWallet = $user->wallet;



        if (!$user->supplier)
            abort(403);

        if (!$userWallet || $userWallet->balance < env("SUPPLIER_QUOTATION_PRICE"))
            abort(403);


        DB::beginTransaction();
        try {


            $data = $request->validated();
            $data["user_id"] = $user->id;
            $quotation = ServiceQoutation::create($data);
            $quotation->load("user", "service");
            $userWallet->balance -= env('SUPPLIER_QUOTATION_PRICE');


            DB::commit();
            return response()->json([
                "data" => $quotation
            ], 201);
        } catch (\Exception $e) {
            DB::rollback(); // If an error occurs, rollback the transaction
            return response()->json(["msg" => "error"], 400);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ServiceQoutation  $serviceQoutation
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $serviceQoutation = ServiceQoutation::findOrFail($id);

        $serviceQoutation->load("user", "service");

        return response()->json($serviceQoutation);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ServiceQoutation  $serviceQoutation
     * @return \Illuminate\Http\Response
     */

    public function update(UpdateServiceQuotationRequest $request, $id)
    {

        $serviceQoutation = ServiceQoutation::findOrFail($id);
        $user = auth()->user();

        if (!$user->supplier || $serviceQoutation->user_id != $user->id)
            abort(403);


        DB::beginTransaction();
        try {


            $data = $request->validated();
            $serviceQoutation->update($data);
            $serviceQoutation->load("user", "service");

            DB::commit();
            return response()->json([
                "data" => $serviceQoutation
            ], 200);
        } catch (\Exception $e) {
            DB::rollback(); // If an error occurs, rollback the transaction
            return response()->json(["msg" => "error"], 400);
        }
    }

    public function destroy($id)
    {
        $serviceQoutation = ServiceQoutation::findOrFail($id);

        $serviceQoutation->delete();

        return response()->json([], 204);
    }
}
