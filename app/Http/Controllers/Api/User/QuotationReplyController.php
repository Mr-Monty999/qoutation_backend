<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\User\StoreQuotationReplyRequest;
use App\Http\Requests\Api\User\UpdateQuotationReplyRequest;
use App\Mail\SendQuotationNotificationMail;
use App\Models\Notification;
use App\Models\QuotationReply;
use App\Models\QuotationInvoice;
use App\Models\Quotation;
use App\Models\QuotationProduct;
use App\Models\Transaction;
use App\Notifications\SendQuotationNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class QuotationReplyController extends Controller
{
    public function accept(Quotation $quotation, QuotationReply $reply)
    {
        $user = auth()->user();

        if ($quotation->user_id != $user->id || !$user->buyer)
            return response()->json([], 403);

        if ($quotation->id != $reply->quotation_id)
            return response()->json([], 403);

        if ($quotation->status != "active")
            return response()->json([], 403);

        QuotationReply::where("quotation_product_id", "=", $reply->quotation_product_id)
            ->where(
                "quotation_id",
                "=",
                $reply->quotation_id
            )
            ->update([
                "accepted_by" => null
            ]);


        $reply->update([
            "accepted_by" => $user->id
        ]);

        return response()->json([
            "data" => [
                "message" => "accepted successfully"
            ]
        ]);
    }
    public function store(StoreQuotationReplyRequest $request, $quotationId)
    {

        $isAllNull = true;
        $totalWithoutTax = 0;
        foreach ($request->products as $product) {
            $quotationProduct = QuotationProduct::findOrFail($product["quotation_product_id"]);

            $totalWithoutTax += $product["unit_price"] * $quotationProduct->quantity;

            if ($product["unit_price"] != null) {
                $isAllNull = false;
            }
        }

        if ($isAllNull)
            return response()->json(["message" => trans("messages.please price at least one product") . "!"], 403);

        $user = auth()->user();
        $userWallet = $user->wallet;

        $quotation = Quotation::findOrFail($quotationId);


        if (!$user->supplier)
            return response()->json([], 403);

        if ($quotation->status != "active")
            return response()->json([], 403);

        if (!$userWallet || $userWallet->balance < env("SUPPLIER_QUOTATION_PRICE"))
            return response()->json([
                "message" => trans("messages.you dont have enough money in your wallet !")
            ], 403);

        DB::beginTransaction();
        try {


            $data = $request->validated();

            $tax = 15;
            $taxAmount = ($tax / 100) * $totalWithoutTax;
            $totalIncTax = $taxAmount + $totalWithoutTax;

            $invoice = QuotationInvoice::updateOrCreate([
                "user_id" => $user->id,
                "quotation_id" => $quotation->id,
            ], [
                "user_id" => $user->id,
                "quotation_id" => $quotation->id,
                "total_inc_tax" => $totalIncTax,
                "total_without_tax" => $totalWithoutTax,
                "tax_amount" => $taxAmount,
                "tax_percentage" => $tax
            ]);

            foreach ($data["products"] as $product) {

                $product["user_id"] = $user->id;
                $product["quotation_invoice_id"] = $invoice->id;

                $quotationProduct = QuotationProduct::findOrFail($product["quotation_product_id"]);

                if ($quotationProduct->quotation_id != $quotationId)
                    return;

                $product["quotation_id"] = $quotationProduct->quotation_id;
                if ($product["unit_price"] > 0) {
                    QuotationReply::updateOrCreate([
                        "user_id" => $user->id,
                        "quotation_product_id" => $product["quotation_product_id"],
                        "quotation_id" => $product["quotation_id"],
                        "quotation_invoice_id" => $product["quotation_invoice_id"]
                    ], $product);
                }
            }


            $userWallet->balance -= env('SUPPLIER_QUOTATION_PRICE');
            $userWallet->save();

            $transaction = Transaction::create([
                "user_id" => $user->id,
                "type" => "send_products_quotation",
                "data" => [
                    "quotation_id" => $quotationId,
                ]
            ]);

            // $quotationOwner = Quotation::find($quotationId)->user;
            // Notification::send($quotationOwner, new SendQuotationNotification([
            //     "quotation_id" => $quotationId,
            //     "quotation_id" => $quotation->id,
            //     "sender_id" => $user->id,
            // "messages" => [
            //     "ar" => "لقد قام " . $user->name . " بإرسال عرض سعر لطلبك",
            //     "en" => $user->name . " has sent a quotes for your request"
            // ]
            // ]));

            // Mail::to($quotationOwner)->send(new SendQuotationNotificationMail([
            //     "supplier_name" => $user->name,
            //     "supplier_phone" => $user->phone->country_code . $user->phone->number,
            //     "supplier_email" => $user->email,
            //     "quotation_title" => $quotation->title,
            //     "quotation_price" => $quotation->amount,
            //     "quotation_description" => $quotation->description,
            //     "quotation_id" => $quotationId,
            //     "quotation_id" => $quotation->id

            // ]));

            DB::commit();

            return response()->json([
                "data" => [
                    "msg" => trans("messages.quotation sent successfully")
                ]
            ], 201);
        } catch (\Exception $e) {
            DB::rollback(); // If an error occurs, rollback the transaction
            return response()->json(["msg" => $e->__toString()], 400);
        }
    }


    public function update(UpdateQuotationReplyRequest $request, $quotationId)
    {

        $isAllNull = true;
        $totalWithoutTax = 0;
        foreach ($request->products as $product) {
            $quotationProduct = QuotationProduct::findOrFail($product["quotation_product_id"]);

            $totalWithoutTax += $product["unit_price"] * $quotationProduct->quantity;

            if ($product["unit_price"] != null) {
                $isAllNull = false;
            }
        }

        if ($isAllNull)
            return response()->json(["message" => trans("messages.please price at least one product") . "!"], 403);

        $user = auth()->user();
        $userWallet = $user->wallet;

        $quotation = Quotation::findOrFail($quotationId);


        if (!$user->supplier)
            return response()->json([], 403);

        if ($quotation->status != "active")
            return response()->json([], 403);

        if (!$userWallet || $userWallet->balance < env("SUPPLIER_QUOTATION_PRICE"))
            return response()->json([
                "message" => trans("messages.you dont have enough money in your wallet !")
            ], 403);

        DB::beginTransaction();
        try {


            $data = $request->validated();

            $tax = 15;
            $taxAmount = ($tax / 100) * $totalWithoutTax;
            $totalIncTax = $taxAmount + $totalWithoutTax;

            $invoice = QuotationInvoice::updateOrCreate([
                "user_id" => $user->id,
                "quotation_id" => $quotation->id,
            ], [
                "user_id" => $user->id,
                "quotation_id" => $quotation->id,
                "total_inc_tax" => $totalIncTax,
                "total_without_tax" => $totalWithoutTax,
                "tax_amount" => $taxAmount,
                "tax_percentage" => $tax
            ]);

            foreach ($data["products"] as $product) {

                $product["user_id"] = $user->id;
                $product["quotation_invoice_id"] = $invoice->id;

                $quotationProduct = QuotationProduct::findOrFail($product["quotation_product_id"]);

                if ($quotationProduct->quotation_id != $quotationId)
                    return;

                $product["quotation_id"] = $quotationProduct->quotation_id;
                $product["unit_price"] = $product["unit_price"] ? $product["unit_price"] : 0;
                QuotationReply::updateOrCreate([
                    "user_id" => $user->id,
                    "quotation_product_id" => $product["quotation_product_id"],
                    "quotation_id" => $product["quotation_id"],
                    "quotation_invoice_id" => $product["quotation_invoice_id"]
                ], $product);
            }


            $userWallet->balance -= env('SUPPLIER_QUOTATION_PRICE');
            $userWallet->save();

            $transaction = Transaction::create([
                "user_id" => $user->id,
                "type" => "send_products_quotation",
                "data" => [
                    "quotation_id" => $quotationId,
                ]
            ]);
            DB::commit();

            return response()->json([
                "data" => [
                    "msg" => trans("messages.quotation updated successfully")
                ]
            ], 200);
        } catch (\Exception $e) {
            DB::rollback(); // If an error occurs, rollback the transaction
            return response()->json(["msg" => $e->__toString()], 400);
        }
    }
}
