<?php

namespace App\Services;

use App\Models\Buyer;
use App\Models\Supplier;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
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

    public static function store($request, $userId)
    {

        $data = $request->validated();

        $data["user_id"] = $userId;

        if ($request->hasFile("image")) {
            $fileName = time() . '-' . $request->file("image")->getClientOriginalName();

            $data["image"] = $request->file("image")->storeAs("images/buyers", $fileName, "public");
        }
        if ($request->hasFile("commercial_record_image")) {
            $fileName = time() . '-' . $request->file("commercial_record_image")->getClientOriginalName();

            $data["commercial_record_image"] = $request->file("commercial_record_image")->storeAs("images/suppliers/commercial-records", $fileName, "public");
        }

        $buyer = Buyer::create($data);


        return $buyer;
    }
}
