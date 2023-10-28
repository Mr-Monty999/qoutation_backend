<?php

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

Route::group(["prefix" => "v1"], function () {
    // Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    //     return $request->user();
    // });

    //////// Buyers Routes /////////
    Route::apiResource("buyers", BuyerController::class, [
        "as" => "user"
    ]);


    //////// Buyers Routes /////////
    Route::apiResource("suppliers", SupplierController::class, [
        "as" => "user"
    ]);
});
