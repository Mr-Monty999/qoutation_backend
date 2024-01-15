<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\User\UpdateBuyerProfileRequest;
use App\Http\Requests\StoreBuyerRequest;
use App\Http\Requests\UpdateBuyerRequest;
use App\Models\Buyer;
use App\Models\City;
use App\Models\Country;
use App\Models\Neighbourhood;
use App\Models\Service;

class BuyerController extends Controller
{
    public function updateProfile(UpdateBuyerProfileRequest $request)
    {
        $data = $request->validated();

        $country = Country::findOrFail($request->country_id);
        $city = City::findOrFail($request->city_id);
        $neighbourhood = Neighbourhood::findOrFail($request->neighbourhood_id);

        if ($city->country_id != $country->id || $neighbourhood->city_id != $city->id)
            return response()->json([], 403);


        $user = auth()->user();

        if (!$user->buyer)
            return response()->json([], 403);

        $user->update($data);
        $user->load("buyer");
        $user->buyer->update($data);

        return response()->json([
            "data" => $user
        ]);
    }
}
