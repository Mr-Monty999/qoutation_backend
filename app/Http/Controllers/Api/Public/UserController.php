<?php

namespace App\Http\Controllers\Api\Public;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function show(User $user)
    {
        $user->load("supplier", "buyer", "phone");
        return response()->json([
            "data" => $user
        ]);
    }
}
