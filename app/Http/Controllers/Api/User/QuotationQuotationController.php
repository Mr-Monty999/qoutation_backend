<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\User\StoreQuotationQuotationRequest;
use App\Http\Requests\Api\User\UpdateQuotationQuotationRequest;
use App\Mail\AcceptQuotationMail;
use App\Mail\SendQuotationNotificationMail;
use App\Models\Quotation;
use App\Models\QuotationQuotation;
use App\Models\Transaction;
use App\Notifications\AcceptQuotationNotification;
use App\Notifications\SendQuotationNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;

class QuotationQuotationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    // public function allUserQuotations(Request $request)
    // {
    //     $user = auth()->user();

    //     $quotations = QuotationQuotation::with("user.buyer", "user.supplier", "quotation.user.buyer");

    //     $quotations->where("user_id", $user->id);


    //     $quotations =  $quotations->get();

    //     return response()->json([
    //         "data" => $quotations
    //     ]);
    // }

    // public function acceptQuotation(Request $request, Quotation $quotation, QuotationQuotation $quotationQuotation)
    // {

    //     $user = auth()->user();
    //     if ($quotation->user_id != $user->id)
    //         return response()->json([], 403);


    //     if ($quotationQuotation->accepted_by != null)
    //         return response()->json([], 403);

    //     $supplier = $quotationQuotation->user;

    //     DB::beginTransaction();
    //     try {
    //         $quotationQuotation->update([
    //             "accepted_by" => $user->id
    //         ]);


    //         $transaction = Transaction::create([
    //             "user_id" => $user->id,
    //             "type" => "accept_quotation",
    //             "data" => [
    //                 "quotation_id" => $quotation->id,
    //                 "quotation_id" => $quotationQuotation->id,
    //                 "amount" => $quotationQuotation->amount
    //             ]
    //         ]);

    //         Notification::send($supplier, new AcceptQuotationNotification([
    //             "quotation_id" => $quotation->id,
    //             "quotation_id" => $quotationQuotation->id,
    //             "sender_id" => $user->id,
    //         ]));

    //         Mail::to($supplier)->send(new AcceptQuotationMail([
    //             "buyer_name" => $user->name,
    //             "buyer_phone" => $user->phone->country_code . $user->phone->number,
    //             "buyer_email" => $user->email,
    //             "quotation_title" => $quotationQuotation->title,
    //             "quotation_price" => $quotationQuotation->amount,
    //             "quotation_description" => $quotationQuotation->description,
    //             "quotation_id" => $quotation->id,
    //             "quotation_id" => $quotationQuotation->id


    //         ]));


    //         DB::commit();
    //         return response()->json([
    //             "msg" => trans("messages.accepted successfully")
    //         ]);
    //     } catch (\Exception $e) {
    //         DB::rollback(); // If an error occurs, rollback the transaction
    //         return response()->json(["msg" => "error"], 400);
    //     }
    // }
    // public function index(Request $request, $quotationId)
    // {

    //     $user = auth()->user();

    //     $quotations = QuotationQuotation::with("user.buyer", "user.supplier", "quotation.user.buyer");

    //     $quotations->where("quotation_id", $quotationId);

    //     $quotations =  $quotations->get();

    //     return response()->json([
    //         "data" => $quotations
    //     ]);
    // }


    // /**
    //  * Store a newly created resource in storage.
    //  *
    //  * @param  \App\Http\Requests\StoreQuotationQuotationRequest  $request
    //  * @return \Illuminate\Http\Response
    //  */
    // public function store(StoreQuotationQuotationRequest $request, $quotationId)
    // {

    //     $user = auth()->user();
    //     $userWallet = $user->wallet;

    //     $quotation = Quotation::find($quotationId);


    //     if (!$user->supplier)
    //         return response()->json([], 403);

    //     if ($quotation->status != "active")
    //         return response()->json([], 403);

    //     if (!$userWallet || $userWallet->balance < env("SUPPLIER_QUOTATION_PRICE"))
    //         return response()->json([
    //             "message" => trans("messages.you dont have enough money in your wallet !")
    //         ], 403);

    //     $quotationExists = QuotationQuotation::where("quotation_id", $quotationId)
    //         ->where("user_id", $user->id)
    //         ->exists();

    //     if ($quotationExists)
    //         return response()->json([], 403);

    //     DB::beginTransaction();
    //     try {


    //         $data = $request->validated();
    //         $data["user_id"] = $user->id;
    //         $data["quotation_id"] = $quotationId;
    //         $quotation = QuotationQuotation::create($data);
    //         $userWallet->balance -= env('SUPPLIER_QUOTATION_PRICE');
    //         $userWallet->save();

    //         $quotation->load("user.wallet", "quotation.user.buyer");

    //         $transaction = Transaction::create([
    //             "user_id" => $user->id,
    //             "type" => "send_quotation",
    //             "data" => [
    //                 "quotation_id" => $quotationId,
    //                 "quotation_id" => $quotation->id,
    //                 "amount" => $quotation->amount
    //             ]
    //         ]);

    //         $quotationOwner = Quotation::find($quotationId)->user;
    //         Notification::send($quotationOwner, new SendQuotationNotification([
    //             "quotation_id" => $quotationId,
    //             "quotation_id" => $quotation->id,
    //             "sender_id" => $user->id,
    //             // "messages" => [
    //             //     "ar" => "لقد قام " . $user->name . " بإرسال عرض سعر لطلبك",
    //             //     "en" => $user->name . " has sent a quotes for your request"
    //             // ]
    //         ]));

    //         Mail::to($quotationOwner)->send(new SendQuotationNotificationMail([
    //             "supplier_name" => $user->name,
    //             "supplier_phone" => $user->phone->country_code . $user->phone->number,
    //             "supplier_email" => $user->email,
    //             "quotation_title" => $quotation->title,
    //             "quotation_price" => $quotation->amount,
    //             "quotation_description" => $quotation->description,
    //             "quotation_id" => $quotationId,
    //             "quotation_id" => $quotation->id

    //         ]));

    //         DB::commit();

    //         return response()->json([
    //             "data" => $quotation
    //         ], 201);
    //     } catch (\Exception $e) {
    //         DB::rollback(); // If an error occurs, rollback the transaction
    //         return response()->json(["msg" => $e->__toString()], 400);
    //     }
    // }

    // /**
    //  * Display the specified resource.
    //  *
    //  * @param  \App\Models\QuotationQuotation  $quotationQuotation
    //  * @return \Illuminate\Http\Response
    //  */
    // public function show($quotationId, $quotationId)
    // {


    //     $quotation = Quotation::findOrFail($quotationId);
    //     $quotationQuotation = QuotationQuotation::findOrFail($quotationId);

    //     $user = auth()->user();

    //     if ($user->id != $quotationQuotation->user_id)
    //         return response()->json([], 403);


    //     $quotationQuotation->load("user.buyer", "user.supplier", "quotation.user.buyer", "quotation.activities");
    //     $quotationQuotation->quotation->loadCount("quotationQuotations");

    //     return response()->json($quotationQuotation);
    // }

    // /**
    //  * Show the form for editing the specified resource.
    //  *
    //  * @param  \App\Models\QuotationQuotation  $quotationQuotation
    //  * @return \Illuminate\Http\Response
    //  */

    // public function update(UpdateQuotationQuotationRequest $request, $quotationId, $quotationId)
    // {

    //     $quotation = Quotation::findOrFail($quotationId);
    //     $quotationQuotation = QuotationQuotation::findOrFail($quotationId);
    //     $user = auth()->user();

    //     if ($quotation->status == "completed")
    //         return response()->json([], 403);

    //     if (!$user->supplier || $quotationQuotation->user_id != $user->id || $quotationQuotation->accepted_by)
    //         return response()->json([], 403);


    //     DB::beginTransaction();
    //     try {


    //         $data = $request->validated();
    //         $quotationQuotation->update($data);
    //         $quotationQuotation->load("user.buyer", "user.supplier", "quotation.user.buyer");

    //         DB::commit();
    //         return response()->json([
    //             "data" => $quotationQuotation
    //         ], 200);
    //     } catch (\Exception $e) {
    //         DB::rollback(); // If an error occurs, rollback the transaction
    //         return response()->json(["msg" => "error"], 400);
    //     }
    // }

    // public function destroy($quotationId, $quotationId)
    // {
    //     $quotationQuotation = QuotationQuotation::findOrFail($quotationId);

    //     $quotationQuotation->delete();

    //     return response()->json([], 204);
    // }
}
