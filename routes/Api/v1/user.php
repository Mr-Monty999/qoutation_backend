<?php

use App\Http\Controllers\Api\Auth\RegisterController;
use App\Http\Controllers\Api\BuyerController;
use App\Http\Controllers\Api\SupplierController;
use App\Http\Controllers\Api\User\ServiceController;
use App\Http\Controllers\Api\User\UserController;
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
        Route::resource("services", ServiceController::class, [
            "as" => "user"
        ]);


        //// get auth user services ////
        Route::get("/auth/services", [ServiceController::class, "userServices"]);
    });
});
