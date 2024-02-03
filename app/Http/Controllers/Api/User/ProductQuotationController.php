<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\User\StoreProductQuotationRequest;
use App\Mail\SendQuotationNotificationMail;
use App\Models\Notification;
use App\Models\ProductQuotation;
use App\Models\QuotationInvoice;
use App\Models\Service;
use App\Models\ServiceProduct;
use App\Models\ServiceQuotation;
use App\Models\Transaction;
use App\Notifications\SendQuotationNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class ProductQuotationController extends Controller
{
    public function store(StoreProductQuotationRequest $request, $serviceId)
    {

        $isAllNull = true;
        $totalWithoutTax = 0;
        foreach ($request->products as $product) {
            $serviceProduct = ServiceProduct::findOrFail($product["service_product_id"]);

            $totalWithoutTax += $product["unit_price"] * $serviceProduct->quantity;

            if ($product["unit_price"] != null) {
                $isAllNull = false;
            }
        }

        if ($isAllNull)
            return response()->json(["message" => trans("messages.please price at least one product") . "!"], 403);

        $user = auth()->user();
        $userWallet = $user->wallet;

        $service = Service::findOrFail($serviceId);


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


            $data = $request->validated();

            $tax = 15;
            $taxAmount = ($tax / 100) * $totalWithoutTax;
            $totalIncTax = $taxAmount + $totalWithoutTax;

            $invoice = QuotationInvoice::updateOrCreate([
                "user_id" => $user->id,
                "service_id" => $service->id,
            ], [
                "user_id" => $user->id,
                "service_id" => $service->id,
                "total_inc_tax" => $totalIncTax,
                "total_without_tax" => $totalWithoutTax,
                "tax_amount" => $taxAmount,
                "tax_percentage" => $tax
            ]);

            foreach ($data["products"] as $product) {

                $product["user_id"] = $user->id;
                $product["quotation_invoice_id"] = $invoice->id;

                $serviceProduct = ServiceProduct::findOrFail($product["service_product_id"]);

                if ($serviceProduct->service_id != $serviceId)
                    return;

                $product["service_id"] = $serviceProduct->service_id;
                if ($product["unit_price"] > 0) {
                    ProductQuotation::updateOrCreate([
                        "user_id" => $user->id,
                        "service_product_id" => $product["service_product_id"],
                        "service_id" => $product["service_id"],
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
                    "service_id" => $serviceId,
                ]
            ]);

            // $serviceOwner = Service::find($serviceId)->user;
            // Notification::send($serviceOwner, new SendQuotationNotification([
            //     "service_id" => $serviceId,
            //     "quotation_id" => $quotation->id,
            //     "sender_id" => $user->id,
            // "messages" => [
            //     "ar" => "لقد قام " . $user->name . " بإرسال عرض سعر لطلبك",
            //     "en" => $user->name . " has sent a quotes for your request"
            // ]
            // ]));

            // Mail::to($serviceOwner)->send(new SendQuotationNotificationMail([
            //     "supplier_name" => $user->name,
            //     "supplier_phone" => $user->phone->country_code . $user->phone->number,
            //     "supplier_email" => $user->email,
            //     "quotation_title" => $quotation->title,
            //     "quotation_price" => $quotation->amount,
            //     "quotation_description" => $quotation->description,
            //     "service_id" => $serviceId,
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
}
