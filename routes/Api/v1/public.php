<?php

use App\Http\Controllers\Api\Auth\ForgetPasswordController;
use App\Http\Controllers\Api\Auth\LoginController;
use App\Http\Controllers\Api\Auth\OtpController;
use App\Http\Controllers\Api\Auth\RegisterController;
use App\Http\Controllers\Api\BuyerController;
use App\Http\Controllers\Api\Public\ActivityController;
use App\Http\Controllers\Api\Public\CountryController;
use App\Http\Controllers\Api\SupplierController;
use App\Http\Controllers\Api\Public\UserController;
use App\Http\Controllers\Api\Public\CityController;
use App\Http\Controllers\Api\Public\NeighbourhoodController;
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

Route::group(["prefix" => "v1/public"], function () {

    Route::get('activities', [ActivityController::class, "index"]);

    Route::get('countries', [CountryController::class, "index"]);

    Route::get('countries/{country}/cities', [CityController::class, "getCountryCities"]);

    Route::get('cities/{city}/neighbourhoods', [NeighbourhoodController::class, "getCityNeighbourhoods"]);


    Route::get("users/{user}", [UserController::class, "show"]);
});
