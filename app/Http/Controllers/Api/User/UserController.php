<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\User\UpdateEmailRequest;
use App\Http\Requests\Api\User\UpdatePasswordRequest;
use App\Models\Message;
use App\Models\MessageRecipient;
use App\Models\UserOtp;
use App\Services\UserOtpService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function getAuthUser()
    {
        $user = Auth::user();
        $user->load(["supplier", "buyer", "admin", "wallet", "country", "city", "neighbourhood", "activities", "phone","currentSubscription"])
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

    public function updatePassword(UpdatePasswordRequest $request)
    {
        $user = auth()->user();
        if (!Hash::check($request->old_password, $user->getAuthPassword()))
            return response()->json([], 403);


        $user->update([
            "password" => Hash::make($request->new_password)
        ]);

        return response()->json([
            "data" => $user
        ]);
    }
    public function updateEmail(UpdateEmailRequest $request)
    {

        $user = auth()->user();
        $verifyOtp = UserOtpService::checkOtpIsVerified($request->new_email, $request->otp);

        if (!$verifyOtp)
            return response()->json([], 403);

        $user->update([
            "email" => $request->new_email,
            "email_verified_at" => now()
        ]);

        return response()->json([
            "data" => $user
        ]);
    }
    public function verifyEmail(Request $request)
    {
        $user = auth()->user();
        $checkIfOtpIsVerified = UserOtpService::checkOtpIsVerified($user->email, $request->otp_code);

        if (!$checkIfOtpIsVerified)
            return response()->json([], 403);


        $user->update([
            "email_verified_at" => now()
        ]);

        $user->load(["supplier", "buyer", "admin", "wallet", "country", "city", "neighbourhood", "activities", "phone"])
            ->loadCount(
                "notifications",
                "unreadNotifications"
            );

        return response()->json([
            "data" => $user
        ]);
    }
}
