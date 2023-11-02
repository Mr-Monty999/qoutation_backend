<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function getAuthUser()
    {
        $user = Auth::user();
        $user->load(["supplier", "buyer", "admin"]);


        return response()->json($user);
    }
}
