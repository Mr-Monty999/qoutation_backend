<?php

namespace App\Services;

use App\Models\Supplier;
use App\Models\User;

/**
 * Class SupplierService.
 */
class SupplierService
{


    public static function store($request, $userId)
    {

        $data = $request->validated();

        $data["user_id"] = $userId;


        if ($request->hasFile("image")) {
            $fileName = time() . '-' . $request->file("image")->getClientOriginalName();

            $data["image"] = $request->file("image")->storeAs("images/suppliers", $fileName, "public");
        }
        if ($request->hasFile("commercial_record_image")) {
            $fileName = time() . '-' . $request->file("commercial_record_image")->getClientOriginalName();

            $data["commercial_record_image"] = $request->file("commercial_record_image")->storeAs("images/suppliers/commercial-records", $fileName, "public");
        }

        $buyer = Supplier::create($data);


        return $buyer;
    }
}
