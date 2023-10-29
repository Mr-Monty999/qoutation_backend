<?php

namespace App\Services;

use App\Models\Buyer;
use App\Models\Supplier;
use App\Models\User;
use Illuminate\Support\Facades\Storage;

/**
 * Class BuyerService.
 */
class BuyerService
{

    public static function getAllPaginated($perPage = 5)
    {
        $suppliers = Supplier::paginate($perPage);

        return $suppliers;
    }

    public static function store($request)
    {

        $data = $request->validated();

        $user = User::ceate($data);
        $data["user_id"] = $user->id;

        if ($request->hasFile("photo")) {
            $fileName = time() . '.' . $request->file("photo")->getClientOriginalExtension();

            $data["photo"] = $request->file("photo")->storeAs("public/buyers", $fileName);
        }
        $buyer = Buyer::create($data);

        UserOtpService::sendEmailOtp($user);

        return $buyer;
    }
}
