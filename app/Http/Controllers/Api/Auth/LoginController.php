<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public  function login(Request $request)
    {
        $login = Auth::attempt([
            "email" => $request->email,
            "password" => $request->password
        ]);

        if (!$login)
            return response()->json([
                "message" => trans('auth.failed')
            ], 401);

        $user = Auth::user();
        $user->load(["supplier", "buyer", "admin", "phone"]);
        $user->token = $user->createToken(uniqid())->plainTextToken;


        return response()->json($user);
    }
}
