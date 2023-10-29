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

    Route::post("login", [LoginController::class, "login"]);

    ///// Forget Password routes ////
    Route::post("forget-password/change-password", [ForgetPasswordController::class, "changePassword"]);
    Route::post("forget-password/send-otp", [OtpController::class, "sendForgetPasswordOtp"]);
    Route::post("forget-password/verify-otp", [OtpController::class, "verifyForgetPasswordOtp"]);

    ///// Register Routes /////
    Route::post("register/buyer", [RegisterController::class, "registerBuyer"]);
    Route::post("register/supplier", [RegisterController::class, "registerSupplier"]);
    Route::post("register/send-otp", [OtpController::class, "sendRegisterOtp"]);
    Route::post("register/verify-otp", [OtpController::class, "verifyRegisterOtp"]);
});
