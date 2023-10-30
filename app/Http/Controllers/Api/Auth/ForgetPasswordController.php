<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Auth\ResetPasswordRequest;
use App\Models\User;
use App\Services\UserOtpService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ForgetPasswordController extends Controller
{
    public function resetPassword(ResetPasswordRequest $request)
    {
        $user = User::findOrFail($request->user_id);
        $checkOtp = UserOtpService::checkOtpIsVerified($user, $request->otp_code);

        if (!$checkOtp)
            return response()->json([
                "message" => trans("messages.otp is not correct or expired")
            ], 401);

        $user->update([
            "password" => Hash::make($request->password)
        ]);

        return response()->json([
            "message" => trans("messages.password updated successfully")
        ]);
    }
    public function getUser(Request $request)
    {
        $user = User::where("email", $request->email_or_phone)
            ->orWhere("phone", $request->email_or_phone)
            ->firstOrFail();

        return response()->json($user);
    }
}
