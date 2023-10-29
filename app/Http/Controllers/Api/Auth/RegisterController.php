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
use App\Services\UserOtpService;
use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class RegisterController extends Controller
{
    public  function registerBuyer(RegisterBuyerRequest $request)
    {
        $user = UserService::store($request);
        $buyer = BuyerService::store($request, $user->id);
        $otp = UserOtpService::sendEmailOtp($user);

        $user->load("buyer");

        return response()->json($user, 201);
    }
    public  function registerSupplier(RegisterSupplierRequest $request)
    {
        $user = UserService::store($request);
        $supplier = SupplierService::store($request, $user->id);
        $otp = UserOtpService::sendEmailOtp($user);

        $user->load("supplier");

        return response()->json($user, 201);
    }
}
