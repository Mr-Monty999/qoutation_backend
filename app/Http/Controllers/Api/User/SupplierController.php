<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\User\UpdateSupplierProfileRequest;
use App\Http\Requests\StoreSupplierRequest;
use App\Http\Requests\UpdateSupplierRequest;
use App\Models\City;
use App\Models\Country;
use App\Models\Neighbourhood;
use App\Models\Supplier;

class SupplierController extends Controller
{
    public function updateProfile(UpdateSupplierProfileRequest $request)
    {
        $data = $request->validated();

        $country = Country::findOrFail($request->country_id);
        $city = City::findOrFail($request->city_id);
        $neighbourhood = Neighbourhood::findOrFail($request->neighbourhood_id);

        if ($city->country_id != $country->id || $neighbourhood->city_id != $city->id)
            abort(403);

        $user = auth()->user();

        if (!$user->supplier)
            abort(403);

        $user->update($data);
        $user->load("supplier", "activities");
        $user->supplier->update($data);

        return response()->json([
            "data" => $user
        ]);
    }
}
