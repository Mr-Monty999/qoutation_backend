<?php

use App\Http\Controllers\Api\Auth\RegisterController;
use App\Http\Controllers\Api\User\BuyerController;
use App\Http\Controllers\Api\User\NotificationController;
use App\Http\Controllers\Api\User\QuotationController;
use App\Http\Controllers\Api\User\UserController;
use App\Http\Controllers\Api\User\WalletController;
use App\Http\Controllers\Api\User\MessageController;
use App\Http\Controllers\Api\User\QuotationProductController;
use App\Http\Controllers\Api\User\QuotationReplyController;
use App\Http\Controllers\Api\User\ServiceController;
use App\Http\Controllers\Api\User\ServiceReplyController;
use App\Http\Controllers\Api\User\SubscriptionController;
use App\Http\Controllers\Api\User\SupplierController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group(["prefix" => "v1/user", "middleware" => ["auth:sanctum"]], function () {
    // Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    //     return $request->user();
    // });

    Route::get("get-auth-user", [UserController::class, "getAuthUser"]);

    Route::group(["middleware" => ["verified", "only-accepted-supplier"]], function () {

        /////////////// quotations /////////////
        Route::apiResource("quotations", QuotationController::class, [
            "as" => "user"
        ]);

        Route::put("quotations/{quotation}/complete", [QuotationController::class, "markAsCompleted"]);

        Route::post("quotations/{quotation}/replies", [QuotationReplyController::class, "store"]);
        Route::put("quotations/{quotation}/replies", [QuotationReplyController::class, "update"]);
        Route::put("quotations/{quotation}/replies/{reply}/accept", [QuotationReplyController::class, "accept"]);
        Route::put("quotations/{quotation}/replies/{reply}/unaccept", [QuotationReplyController::class, "unAccept"]);

        Route::get("quotations/{quotation}/products/{quotationProduct}", [QuotationProductController::class, "show"]);

        Route::get(
            "quotations/{quotation}/replies/invoices/{invoice}",
            [QuotationReplyController::class, "showInvoice"]
        );



        //// get supplier quotations ////

        Route::get("/supplier/quotations", [QuotationController::class, "supplierQuotations"]);


        //// get buyer quotations ////
        Route::get("/buyer/quotations", [QuotationController::class, "buyerQuotations"]);



        ///// wallets /////
        Route::post("wallet-recharge", [WalletController::class, "recharge"])->middleware("throttle:3,1");
        Route::post("wallet-recharge/success/{uuid}", [WalletController::class, "rechargeSuccess"])
            ->name("user.wallet.recharge.success")->middleware("throttle:3,1");

        Route::post("wallet-recharge/cancelled/{uuid}", [WalletController::class, "rechargeCancelled"])
            ->name("user.wallet.recharge.cancelled")->middleware("throttle:3,1");

        Route::post("wallet-recharge/declined/{uuid}", [WalletController::class, "rechargeDeclined"])
            ->name("user.wallet.recharge.declined")->middleware("throttle:3,1");


        ///// notifications /////
        Route::put("notifications/{notification}/read", [NotificationController::class, "read"]);
        Route::get("notifications/count", [NotificationController::class, "notificationsCount"]);
        Route::put("notifications/readall", [NotificationController::class, "readAllNotifications"]);
        Route::get("notifications", [NotificationController::class, "index"]);



        ////// messages /////
        Route::get("messages", [MessageController::class, "index"]);
        Route::post("messages", [MessageController::class, "store"]);
        Route::get("messages/count", [MessageController::class, "getCount"]);
        Route::get("messages/{message}", [MessageController::class, "showMessage"]);
        Route::get("messages/recipients/{messageRecipient}", [MessageController::class, "showMessageRecipient"]);
        Route::put("messages/recipients/{messageRecipient}/read", [MessageController::class, "readMessageRecipient"]);



        ///// info api ////
        Route::get("info", [UserController::class, "info"]);


        ////// buyers /////
        Route::put("buyer/profile/update", [BuyerController::class, "updateProfile"]);


        ////// suppliers /////
        Route::put("supplier/profile/update", [SupplierController::class, "updateProfile"]);


        ///// users /////
        Route::put("password/update", [UserController::class, "updatePassword"]);
        Route::put("email/update", [UserController::class, "updateEmail"]);


        /////////////// services /////////////
        Route::apiResource("services", ServiceController::class, [
            "as" => "user"
        ]);

        /////////////// service replies /////////////
        Route::put("services/{service}/status", [ServiceController::class, "updateStatus"])->middleware("only-buyer");

        Route::post("services/{service}/replies", [ServiceReplyController::class, "store"])
            ->middleware("only-supplier");

        Route::get("services/{service}/replies/{reply}", [ServiceReplyController::class, "show"])
            ->middleware("only-supplier");

        Route::put("services/{service}/replies/{reply}", [ServiceReplyController::class, "update"])
            ->middleware("only-supplier");

        Route::put("services/{service}/replies/{reply}/accept", [ServiceReplyController::class, "accept"])
            ->middleware("only-buyer");

        Route::put("services/{service}/replies/{reply}/unaccept", [ServiceReplyController::class, "unAccept"])
            ->middleware("only-buyer");


        //// get supplier services ////
        Route::get("/supplier/services", [ServiceController::class, "supplierServices"])->middleware("only-supplier");


        //// get buyer services ////
        Route::get("/buyer/services", [ServiceController::class, "buyerServices"])->middleware("only-buyer");


        /// Subscribe In Pakcge
        Route::post("supplier/packages/subscribe", [SubscriptionController::class, "subscribe"])
            ->middleware("only-supplier");
    });
});
