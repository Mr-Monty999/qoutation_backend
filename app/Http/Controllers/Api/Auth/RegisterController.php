<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Auth\RegisterBuyerRequest;
use App\Http\Requests\Api\Auth\RegisterSupplierRequest;
use App\Models\Buyer;
use App\Models\City;
use App\Models\Country;
use App\Models\Neighbourhood;
use App\Models\Supplier;
use App\Models\User;
use App\Models\UserPhone;
use App\Models\Wallet;
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
        // if (UserService::checkIfValueExists("phone", $request->country_code . $request->phone))
        //     throw  ValidationException::withMessages([
        //         "phone" => trans("validation.unique", ["attribute" => trans("validation.attributes.phone")])
        //     ]);

        $country = Country::findOrFail($request->country_id);
        $city = City::findOrFail($request->city_id);
        $neighbourhood = Neighbourhood::findOrFail($request->neighbourhood_id);

        if ($city->country_id != $country->id || $neighbourhood->city_id != $city->id)
            return response()->json([], 403);

        DB::beginTransaction();

        try {

            $user = UserService::store($request);
            $userPhone = UserPhone::create([
                "user_id" => $user->id,
                "number" => $request->phone,
                "country_code" => $country->code
            ]);
            $wallet = Wallet::create([
                "user_id" => $user->id,
            ]);


            $buyer = BuyerService::store($request, $user->id);
            $otp = UserOtpService::sendEmailOtp($user->email, "email_confirmation");

            $role = Role::findOrCreate("buyer");
            $user->assignRole("buyer");
            $user->load("buyer", "phone");

            DB::commit();
            return response()->json($user, 201);
        } catch (\Exception $e) {
            DB::rollback(); // If an error occurs, rollback the transaction
            return response()->json(["msg" => "error"], 400);
        }
    }
    public  function registerSupplier(RegisterSupplierRequest $request)
    {

        // if (UserService::checkIfValueExists("phone", $request->country_code . $request->phone))
        //     throw  ValidationException::withMessages([
        //         "phone" => trans("validation.unique", ["attribute" => trans("validation.attributes.phone")])
        //     ]);

        $country = Country::findOrFail($request->country_id);
        $city = City::findOrFail($request->city_id);
        $neighbourhood = Neighbourhood::findOrFail($request->neighbourhood_id);

        if ($city->country_id != $country->id || $neighbourhood->city_id != $city->id)
            return response()->json([], 403);

        DB::beginTransaction();

        try {
            $user = UserService::store($request);
            $userPhone = UserPhone::create([
                "user_id" => $user->id,
                "number" => $request->phone,
                "country_code" => $country->code
            ]);
            $wallet = Wallet::create([
                "user_id" => $user->id,
                "balance" => env("WALLET_GIFT") ? env("WALLET_GIFT") : 0
            ]);

            $supplier = SupplierService::store($request, $user->id);
            $otp = UserOtpService::sendEmailOtp($user->email, "email_confirmation");

            $user->activities()->sync($request->activity_ids);

            $role = Role::findOrCreate("supplier");
            $user->assignRole("supplier");
            $user->load("supplier", "activities", "phone");

            DB::commit();
            return response()->json($user, 201);
        } catch (\Exception $e) {
            DB::rollback(); // If an error occurs, rollback the transaction
            return response()->json(["msg" => "error"], 400);
        }
    }
}
