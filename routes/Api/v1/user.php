<?php

use App\Http\Controllers\Api\Auth\RegisterController;
use App\Http\Controllers\Api\BuyerController;
use App\Http\Controllers\Api\SupplierController;
use App\Http\Controllers\Api\User\ServiceController;
use App\Http\Controllers\Api\User\ServiceQoutationController;
use App\Http\Controllers\Api\User\UserController;
use App\Http\Controllers\Api\User\WalletController;
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

    Route::group(["middleware" => ["verified"]], function () {

        /////////////// services /////////////
        Route::apiResource("services", ServiceController::class, [
            "as" => "user"
        ]);


        //// get auth buyer services ////
        Route::get("/auth/buyer/services", [ServiceController::class, "buyerServices"]);



        ///// wallets /////
        Route::post("wallet-recharge", [WalletController::class, "recharge"])->middleware("throttle:3,1");
        Route::post("wallet-recharge/success/{uuid}", [WalletController::class, "rechargeSuccess"])->name("user.wallet.recharge.success")->middleware("throttle:3,1");
        Route::post("wallet-recharge/cancelled/{uuid}", [WalletController::class, "rechargeCancelled"])->name("user.wallet.recharge.cancelled")->middleware("throttle:3,1");
        Route::post("wallet-recharge/declined/{uuid}", [WalletController::class, "rechargeDeclined"])->name("user.wallet.recharge.declined")->middleware("throttle:3,1");


        /////////////// services /////////////
        Route::apiResource("quotations", ServiceQoutationController::class, [
            "as" => "user"
        ]);
    });
});
