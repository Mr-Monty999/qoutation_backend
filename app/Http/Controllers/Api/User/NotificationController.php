<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();

        $notifications = $user->notifications()->latest()->paginate(10);


        return response()->json([
            "data" => $notifications
        ]);
    }
}