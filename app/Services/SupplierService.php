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
        $buyer = Supplier::create($data);


        return $buyer;
    }
}
