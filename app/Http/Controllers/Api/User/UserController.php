<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Models\Message;
use App\Models\MessageRecipient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function getAuthUser()
    {
        $user = Auth::user();
        $user->load(["supplier", "buyer", "admin", "wallet", "country", "city", "neighbourhood"])
            ->loadCount(
                "notifications",
                "unreadNotifications"
            );
        $data = $user;
        $data["unread_messages_count"] = MessageRecipient::where("receiver_id", $user->id)
            ->whereNull("read_at")
            ->count();
        $data["sent_messages_count"] = Message::where("sender_id", $user->id)->count();


        return response()->json([
            "data" => $data
        ]);
    }
    public function info()
    {
        $user = auth()->user();
        $data["notifications_count"] = $user->notifications()->count();
        $data["unread_notifications_count"] = $user->unreadNotifications()->count();

        $data["unread_messages_count"] = MessageRecipient::where("receiver_id", $user->id)
            ->whereNull("read_at")
            ->count();
        $data["sent_messages_count"] = Message::where("sender_id", $user->id)->count();


        return response()->json([
            "data" => $data
        ]);
    }
}
