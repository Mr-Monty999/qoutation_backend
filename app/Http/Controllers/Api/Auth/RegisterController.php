<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Auth\RegisterBuyerRequest;
use App\Http\Requests\Api\Auth\RegisterSupplierRequest;
use App\Models\Buyer;
use App\Models\Supplier;
use App\Models\User;
use App\Services\BuyerService;
use App\Services\SupplierService;
use Illuminate\Http\Request;

class RegisterController extends Controller
{
    public  function registerBuyer(RegisterBuyerRequest $request)
    {
        $buyer = BuyerService::store($request);

        return response()->json($buyer);
    }
    public  function registerSupplier(RegisterSupplierRequest $request)
    {
        $buyer = SupplierService::store($request);

        return response()->json($buyer);
    }
}
