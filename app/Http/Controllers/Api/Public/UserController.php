<?php

namespace App\Http\Controllers\Api\Public;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function show(User $user)
    {
        $user->load("supplier", "buyer", "phone", "city", "neighbourhood", "activities");
        return response()->json([
            "data" => $user
        ]);
    }
}
