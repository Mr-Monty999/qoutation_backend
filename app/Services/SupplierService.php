<?php

namespace App\Services;

use App\Models\Supplier;
use App\Models\User;

/**
 * Class SupplierService.
 */
class SupplierService
{

    public static function store($request)
    {

        $data = $request->validated();

        $user = User::ceate($data);
        $data["user_id"] = $user->id;

        if ($request->hasFile("photo")) {
            $fileName = time() . '.' . $request->file("photo")->getClientOriginalExtension();

            $data["photo"] = $request->file("photo")->storeAs("public/suppliers", $fileName);
        }
        $supplier = Supplier::create($data);

        UserOtpService::sendEmailOtp($user);

        return $supplier;
    }
}
