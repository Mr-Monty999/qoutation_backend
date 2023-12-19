<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\User\StoreServiceQuotationRequest;
use App\Http\Requests\Api\User\UpdateServiceQuotationRequest;
use App\Http\Requests\StoreServiceQoutationRequest;
use App\Http\Requests\UpdateServiceQoutationRequest;
use App\Models\ServiceQoutation;
use Illuminate\Support\Facades\DB;

class ServiceQoutationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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


        DB::beginTransaction();
        try {


            $data = $request->validated();
            $data["user_id"] = $user->id;
            $quotation = ServiceQoutation::create($data);


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
    public function show(ServiceQoutation $serviceQoutation)
    {
        return response()->json($serviceQoutation, 200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ServiceQoutation  $serviceQoutation
     * @return \Illuminate\Http\Response
     */

    public function update(UpdateServiceQuotationRequest $request, ServiceQoutation $serviceQoutation)
    {

        $user = auth()->user();

        if (!$user->supplier || $serviceQoutation->user_id != $user->id)
            abort(403);


        DB::beginTransaction();
        try {


            $data = $request->validated();
            $serviceQoutation->update($data);


            DB::commit();
            return response()->json([
                "data" => $serviceQoutation
            ], 200);
        } catch (\Exception $e) {
            DB::rollback(); // If an error occurs, rollback the transaction
            return response()->json(["msg" => "error"], 400);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ServiceQoutation  $serviceQoutation
     * @return \Illuminate\Http\Response
     */
    public function destroy(ServiceQoutation $serviceQoutation)
    {
        //
    }
}
