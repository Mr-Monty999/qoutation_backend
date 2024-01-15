<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\User\UpdateSupplierProfileRequest;
use App\Http\Requests\StoreSupplierRequest;
use App\Http\Requests\UpdateSupplierRequest;
use App\Models\Supplier;

class SupplierController extends Controller
{
    public function updateProfile(UpdateSupplierProfileRequest $request)
    {
        $data = $request->validated();

        $user = auth()->user();

        if (!$user->supplier)
            abort(403);

        $user->update($data);
        $user->load("supplier");
        $user->supplier->update($data);

        return response()->json([
            "data" => $user
        ]);
    }
}
