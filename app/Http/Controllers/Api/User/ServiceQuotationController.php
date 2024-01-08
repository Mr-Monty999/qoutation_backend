<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\User\StoreServiceQuotationRequest;
use App\Http\Requests\Api\User\UpdateServiceQuotationRequest;
use App\Mail\AcceptQuotationMail;
use App\Mail\SendQuotationNotificationMail;
use App\Models\Service;
use App\Models\ServiceQuotation;
use App\Models\Transaction;
use App\Notifications\AcceptQuotationNotification;
use App\Notifications\SendQuotationNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;

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

    public function acceptQuotation(Request $request, Service $service, ServiceQuotation $serviceQuotation)
    {

        $user = auth()->user();
        if ($service->user_id != $user->id)
            abort(403);


        if ($serviceQuotation->accepted_by != null)
            abort(403);

        $supplier = $serviceQuotation->user;

        DB::beginTransaction();
        try {
            $serviceQuotation->update([
                "accepted_by" => $user->id
            ]);


            $transaction = Transaction::create([
                "user_id" => $user->id,
                "type" => "accept_quotation",
                "data" => [
                    "service_id" => $service->id,
                    "quotation_id" => $serviceQuotation->id,
                    "amount" => $serviceQuotation->amount
                ]
            ]);

            Notification::send($supplier, new AcceptQuotationNotification([
                "service_id" => $service->id,
                "quotation_id" => $serviceQuotation->id,
                "sender_id" => $user->id,
            ]));

            Mail::to($supplier)->send(new AcceptQuotationMail([
                "buyer_name" => $user->name,
                "buyer_phone" => $user->phone,
                "buyer_email" => $user->email,
                "quotation_title" => $serviceQuotation->title,
                "quotation_price" => $serviceQuotation->amount,
                "quotation_description" => $serviceQuotation->description,
                "service_id" => $service->id,
                "quotation_id" => $serviceQuotation->id


            ]));


            DB::commit();
            return response()->json([
                "msg" => trans("messages.accepted successfully")
            ]);
        } catch (\Exception $e) {
            DB::rollback(); // If an error occurs, rollback the transaction
            return response()->json(["msg" => "error"], 400);
        }
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

        $service = Service::find($serviceId);


        if (!$user->supplier)
            abort(403);

        if ($service->status != "active")
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
            $userWallet->balance -= env('SUPPLIER_QUOTATION_PRICE');
            $userWallet->save();

            $quotation->load("user.wallet", "service");

            $transaction = Transaction::create([
                "user_id" => $user->id,
                "type" => "send_quotation",
                "data" => [
                    "service_id" => $serviceId,
                    "quotation_id" => $quotation->id,
                    "amount" => $quotation->amount
                ]
            ]);

            $serviceOwner = Service::find($serviceId)->user;
            Notification::send($serviceOwner, new SendQuotationNotification([
                "service_id" => $serviceId,
                "quotation_id" => $quotation->id,
                "sender_id" => $user->id,
                // "messages" => [
                //     "ar" => "لقد قام " . $user->name . " بإرسال عرض سعر لطلبك",
                //     "en" => $user->name . " has sent a quotes for your request"
                // ]
            ]));

            Mail::to($serviceOwner)->send(new SendQuotationNotificationMail([
                "supplier_name" => $user->name,
                "supplier_phone" => $user->phone,
                "supplier_email" => $user->email,
                "quotation_title" => $quotation->title,
                "quotation_price" => $quotation->amount,
                "quotation_description" => $quotation->description,
                "service_id" => $serviceId,
                "quotation_id" => $quotation->id

            ]));

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


        $service = Service::findOrFail($serviceId);
        $serviceQuotation = ServiceQuotation::findOrFail($quotationId);

        $user = auth()->user();

        if ($user->id != $serviceQuotation->user_id)
            abort(403);


        $serviceQuotation->load("user", "service.user.buyer");
        $serviceQuotation->service->loadCount("serviceQuotations");

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
