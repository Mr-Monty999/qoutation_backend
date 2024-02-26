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
use App\Models\Quotation;
use Illuminate\Support\Facades\DB;

class BuyerController extends Controller
{
    public function updateProfile(UpdateBuyerProfileRequest $request)
    {
        $data = $request->validated();


        DB::beginTransaction();
        try {

            $country = Country::findOrFail($request->country_id);
            $city = City::findOrFail($request->city_id);
            $neighbourhood = Neighbourhood::findOrFail($request->neighbourhood_id);

            if ($city->country_id != $country->id || $neighbourhood->city_id != $city->id)
                return response()->json([], 403);


            $user = auth()->user();

            if (!$user->buyer)
                return response()->json([], 403);

            if ($request->hasFile("image")) {
                $fileName = time() . '-' . $request->file("image")->getClientOriginalName();

                $data["image"] = $request->file("image")->storeAs("images/buyers", $fileName, "public");
            }

            $user->update($data);
            $user->phone->update([
                "number" => $request->phone
            ]);
            $user->load("buyer", "phone");
            $user->buyer->update($data);

            DB::commit();
            return response()->json([
                "data" => $user
            ]);
        } catch (\Exception $e) {
            DB::rollback(); // If an error occurs, rollback the transaction
            return response()->json(["msg" => "error"], 400);
        }
    }
}
