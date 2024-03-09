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
use Illuminate\Support\Facades\DB;

class SupplierController extends Controller
{
    public function updateProfile(UpdateSupplierProfileRequest $request)
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

            if (!$user->supplier)
                return response()->json([], 403);


            if ($request->hasFile("image")) {
                $fileName = time() . '-' . $request->file("image")->getClientOriginalName();

                $data["image"] = $request->file("image")->storeAs("images/suppliers", $fileName, "public");
            }
            if ($request->hasFile("commercial_record_image")) {
                $fileName = time() . '-' . $request->file("commercial_record_image")->getClientOriginalName();

                $data["commercial_record_image"] = $request->file("commercial_record_image")->storeAs("images/suppliers/commercial-records", $fileName, "public");
            }

            $user->update($data);
            $user->phone->update([
                "number" => $request->phone
            ]);
            $user->activities()->sync($request->activity_ids);

            $user->load("supplier", "activities", "phone");
            $user->supplier->update($data);
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
