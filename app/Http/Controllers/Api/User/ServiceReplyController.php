<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Jobs\EmailJob;
use App\Models\Service;
use App\Models\ServiceReply;
use App\Models\Transaction;
use App\Notifications\AcceptServiceReplyNotification;
use App\Notifications\SendServiceNotification;
use App\Notifications\SendServiceReplyNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ServiceReplyController extends Controller
{
    public function unAccept(Service $service, ServiceReply $reply)
    {
        $user = auth()->user();

        if ($service->user_id != $user->id || !$user->buyer)
            return response()->json([], 403);

        if ($service->id != $reply->service_id)
            return response()->json([], 403);

        if ($service->status != "active")
            return response()->json([], 403);

        if ($reply->accepted_by == null)
            return response()->json([], 403);

        $reply->update([
            "accepted_by" => null
        ]);


        return response()->json([
            "data" => [
                "message" => trans("messages.reply undo accept successfully")
            ]
        ]);
    }

    public function accept(Service $service, ServiceReply $reply)
    {
        $user = auth()->user();

        if ($reply->accepted_by != null)
            return response()->json([], 403);

        if ($service->user_id != $user->id || !$user->buyer)
            return response()->json([], 403);

        if ($service->id != $reply->service_id)
            return response()->json([], 403);

        if ($service->status != "active")
            return response()->json([], 403);

        ServiceReply::where("service_id", "=", $service->id)
            ->update([
                "accepted_by" => null
            ]);

        DB::beginTransaction();
        try {


            $reply->update([
                "accepted_by" => $user->id
            ]);


            $supplier = $reply->user;
            $supplier->notify(new AcceptServiceReplyNotification([
                "service_id" => $service->id,
                "service_reply_id" => $reply->id,
                "sender_id" => $user->id,
                "messages" => [
                    "ar" => "لقد قام " . $user->name . "قام بقبول ردك للخدمة",
                    "en" => $user->name . " accept your service reply"
                ]
            ]));

            EmailJob::dispatch([
                "type" => "accept_service_reply",
                "target_email" => $supplier->email,
                "buyer_name" => $user->name,
                "buyer_phone" => $user->phone->country_code . $user->phone->number,
                "buyer_email" => $user->email,
                "service_reply_title" => $service->title,
                "service_reply_price" => $service->price,
                "service_reply_description" => $service->description,
                "service_id" => $service->id,
                "service_reply_id" => $reply->id,
            ]);

            DB::commit();

            return response()->json([
                "data" => [
                    "message" => trans("messages.accepted successfully")
                ]
            ]);
        } catch (\Exception $e) {
            DB::rollback(); // If an error occurs, rollback the transaction
            return response()->json(["msg" => $e->__toString()], 400);
        }
    }
    public function store(Request $request, Service $service)
    {

        $data = $this->validate($request, [
            "price" => "required|numeric|min:1",
            "title" => "nullable|string",
            "description" => "nullable|string",
        ], [], [
            // "title" => trans("messages.title"),
            "description" => trans("messages.description")
        ]);

        $user = auth()->user();
        $userWallet = $user->wallet;

        $data["service_id"] = $service->id;
        $data["user_id"] = $user->id;

        if (!$user->supplier)
            return response()->json([], 403);

        if ($service->status != "active")
            return response()->json([], 403);

        if (!$userWallet || $userWallet->balance < env("SUPPLIER_QUOTATION_PRICE"))
            return response()->json([
                "message" => trans("messages.you dont have enough money in your wallet !")
            ], 403);

        DB::beginTransaction();
        try {




            $serviceReply =  ServiceReply::updateOrCreate([
                "service_id" => $service->id,
                "user_id" => $user->id
            ], $data);

            $userWallet->balance -= env('SUPPLIER_QUOTATION_PRICE');
            $userWallet->save();

            $transaction = Transaction::create([
                "user_id" => $user->id,
                "type" => "send_products_service",
                "data" => [
                    "service_id" => $service->id,
                ]
            ]);

            $serviceOwner = Service::find($service->id)->user;
            $serviceOwner->notify(new SendServiceReplyNotification([
                "service_id" => $service->id,
                "service_reply_id" => $serviceReply->id,
                "sender_id" => $user->id,
                "messages" => [
                    "ar" => "لقد قام " . $user->name . " بإرسال رد على خدمتك المطلوبة",
                    "en" => $user->name . " has sent a reply for your requested service"
                ]
            ]));

            EmailJob::dispatch([
                "type" => "send_service_reply",
                "target_email" => $serviceOwner->email,
                "supplier_name" => $user->name,
                "supplier_phone" => $user->phone->country_code . $user->phone->number,
                "supplier_email" => $user->email,
                "service_reply_title" => $service->title,
                "service_reply_price" => $service->price,
                "service_reply_description" => $service->description,
                "service_id" => $service->id,
                "service_reply_id" => $serviceReply->id,

            ]);

            DB::commit();

            return response()->json([
                "data" => [
                    "msg" => trans("messages.reply sent successfully")
                ]
            ], 201);
        } catch (\Exception $e) {
            DB::rollback(); // If an error occurs, rollback the transaction
            return response()->json(["msg" => $e->__toString()], 400);
        }
    }


    public function update(Request $request, Service $service, ServiceReply $reply)
    {


        $data = $this->validate($request, [
            "price" => "required|numeric|min:1",
            "title" => "nullable|string",
            "description" => "nullable|string",
        ], [], [
            "title" => trans("messages.title"),
            "description" => trans("messages.description")
        ]);

        $user = auth()->user();
        $userWallet = $user->wallet;

        $data["service_id"] = $service->id;
        $data["user_id"] = $user->id;


        if (!$user->supplier)
            return response()->json([], 403);

        if ($service->status != "active" || $reply->accepted_by != null)
            return response()->json([], 403);

        // if (!$userWallet || $userWallet->balance < env("SUPPLIER_QUOTATION_PRICE"))
        //     return response()->json([
        //         "message" => trans("messages.you dont have enough money in your wallet !")
        //     ], 403);

        DB::beginTransaction();
        try {


            $reply->update($data);

            $transaction = Transaction::create([
                "user_id" => $user->id,
                "type" => "update_products_service",
                "data" => [
                    "service_id" => $service->id,
                ]
            ]);

            // $serviceOwner = Service::find($service->id)->user;

            // $lastNotification = $serviceOwner
            //     ->notifications()
            //     ->where("type", UpdateServiceReplyNotification::class)
            //     ->whereJsonContains("data->sender_id", $user->id)
            //     ->latest()
            //     ->first();
            // if (!$lastNotification || Carbon::parse($lastNotification->created_at)->addMinutes(5) < now()) {
            //     $serviceOwner->notify(new UpdateServiceReplyNotification([
            //         "service_id" => $service->id,
            //         "invoice_id" => $invoice->id,
            //         "sender_id" => $user->id,
            //         "messages" => [
            //             "ar" => "لقد قام " . $user->name . " بتعديل عرض سعره لطلبك",
            //             "en" => $user->name . " has sent a quotes for your request"
            //         ]
            //     ]));
            //     EmailJob::dispatch([
            //         "type" => "update_service_reply",
            //         "target_email" => $serviceOwner->email,
            //         "supplier_name" => $user->name,
            //         // "supplier_phone" => $user->phone->country_code . $user->phone->number,
            //         // "supplier_email" => $user->email,
            //         // "service_reply_title" => $service->title,
            //         // "service_reply_price" => $service->amount,
            //         // "service_reply_description" => $service->description,
            //         "service_id" => $service->id,
            //         "invoice_id" => $invoice->id
            //     ]);
            // }

            DB::commit();



            return response()->json([
                "data" => [
                    "msg" => trans("messages.reply updated successfully")
                ]
            ], 200);
        } catch (\Exception $e) {
            DB::rollback(); // If an error occurs, rollback the transaction
            return response()->json(["msg" => $e->__toString()], 400);
        }
    }

    public function show(Request $request, Service $service, ServiceReply $reply)
    {

        $user = auth()->user();

        if ($service->id != $reply->service_id || $reply->user_id != $user->id)
            return response()->json([], 403);

        return response()->json([
            "data" => $reply
        ]);
    }
}
