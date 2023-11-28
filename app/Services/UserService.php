<?php

namespace App\Services;

use App\Models\Country;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

/**
 * Class UserService.
 */
class UserService
{

    public static function store($request)
    {

        $data = $request->validated();

        $country = Country::findOrFail($data["country_id"]);


        $data["password"] = Hash::make($data["password"]);

        $user = User::create($data);
        $data = $user;
        $data["token"] = $user->createToken(uniqid())->plainTextToken;

        $user->activities()->sync($data["acitivity_ids"]);

        return $user;
    }

    public static function checkIfValueExists($column, $value)
    {
        $check = User::where($column, $value)->exists();
        return $check;
    }
}
