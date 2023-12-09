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
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Spatie\Permission\Models\Role;

class RegisterController extends Controller
{
    public  function registerBuyer(RegisterBuyerRequest $request)
    {
        if (UserService::checkIfValueExists("phone", $request->country_code . $request->phone))
            throw  ValidationException::withMessages([
                "phone" => trans("validation.unique", ["attribute" => trans("validation.attributes.phone")])
            ]);

        DB::beginTransaction();

        try {

            $user = UserService::store($request);
            $buyer = BuyerService::store($request, $user->id);
            $otp = UserOtpService::sendEmailOtp($user);

            $role = Role::findOrCreate("buyer");
            $user->assignRole("buyer");
            $user->load("buyer");

            DB::commit();
            return response()->json($user, 201);
        } catch (\Exception $e) {
            DB::rollback(); // If an error occurs, rollback the transaction
            return response()->json(["msg" => "error"], 400);
        }
    }
    public  function registerSupplier(RegisterSupplierRequest $request)
    {

        $request->merge(["activity_ids" => explode(",", $request->activity_ids)]);


        if (UserService::checkIfValueExists("phone", $request->country_code . $request->phone))
            throw  ValidationException::withMessages([
                "phone" => trans("validation.unique", ["attribute" => trans("validation.attributes.phone")])
            ]);

        DB::beginTransaction();

        try {
            $user = UserService::store($request);
            $supplier = SupplierService::store($request, $user->id);
            $otp = UserOtpService::sendEmailOtp($user);

            $user->activities()->sync($request->activity_ids);

            $role = Role::findOrCreate("supplier");
            $user->assignRole("supplier");
            $user->load("supplier", "activities");

            DB::commit();
            return response()->json($user, 201);
        } catch (\Exception $e) {
            DB::rollback(); // If an error occurs, rollback the transaction
            return response()->json(["msg" => "error"], 400);
        }
    }
}
