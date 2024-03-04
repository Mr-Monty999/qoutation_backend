<?php

use App\Http\Controllers\Api\Admin\ActivityController;
use App\Http\Controllers\Api\Admin\CityController;
use App\Http\Controllers\Api\Admin\CountryController;
use App\Http\Controllers\Api\Admin\NeighbourhoodController;
use App\Http\Controllers\Api\Admin\ProfileController;
use App\Http\Controllers\Api\Admin\SettingController;
use App\Http\Controllers\Api\Admin\SupplierController;
use App\Http\Controllers\Api\Auth\RegisterController;
use App\Http\Controllers\Api\BuyerController;
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

Route::group(["prefix" => "v1/admin", "middleware" => ["auth:sanctum", "only-admin"]], function () {

    //// suppliers ////
    Route::get("suppliers", [SupplierController::class, "index"]);
    Route::put("suppliers/{supplier}/accept", [SupplierController::class, "accept"]);


    //// activities ///
    Route::apiResource("activities", ActivityController::class);

    //// countries ///
    Route::apiResource("countries", CountryController::class);

    //// cities ///
    Route::apiResource("cities", CityController::class);

    //// neighbourhoods ///
    Route::apiResource("neighbourhoods", NeighbourhoodController::class);

    //// settings ////
    Route::get("settings", [SettingController::class, "index"]);
    Route::put("settings", [SettingController::class, "update"]);


    //// profile ////
    Route::put("profile", [ProfileController::class, "update"]);
});
