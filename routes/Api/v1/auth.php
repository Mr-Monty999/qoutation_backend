<?php

use App\Http\Controllers\Api\Auth\ForgetPasswordController;
use App\Http\Controllers\Api\Auth\LoginController;
use App\Http\Controllers\Api\Auth\OtpController;
use App\Http\Controllers\Api\Auth\RegisterController;
use App\Http\Controllers\Api\BuyerController;
use App\Http\Controllers\Api\SupplierController;
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

Route::group(["prefix" => "v1/auth"], function () {



    Route::post("login", [LoginController::class, "login"])->middleware("throttle:3,1");

    ///// Reset Password routes ////
    Route::post("reset-password", [ForgetPasswordController::class, "resetPassword"]);

    ///// Register Routes /////
    Route::post("register/buyer", [RegisterController::class, "registerBuyer"]);
    Route::post("register/supplier", [RegisterController::class, "registerSupplier"]);

    ////////////// Otp Routes ////////
    Route::post("send-otp", [OtpController::class, "sendOtp"])->middleware("throttle:3,1");
    Route::post("verify-otp", [OtpController::class, "verifyOtp"])->middleware("throttle:3,1");
});
