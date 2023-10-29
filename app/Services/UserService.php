<?php

namespace App\Services;

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

        $data["password"] = Hash::make($data["password"]);
        $data["phone"]  = $data["country_code"] . $data["phone"];

        $user = User::create($data);
        $data = $user;
        $data["token"] = $user->createToken(uniqid())->plainTextToken;

        return $user;
    }
}
