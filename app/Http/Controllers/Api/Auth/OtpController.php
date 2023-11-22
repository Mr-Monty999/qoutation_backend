<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Auth\SendRegisterOtp;
use App\Http\Requests\Api\Auth\VerifyRegisterOtp;
use App\Models\User;
use App\Services\UserOtpService;
use Illuminate\Http\Request;

class OtpController extends Controller
{
    public function sendOtp(SendRegisterOtp $request)
    {
        $user = User::where("email", $request->email_or_phone)
            ->orWhere("phone", $request->email_or_phone)
            ->firstOrFail();

        $isEmail = filter_var($request->email_or_phone, FILTER_VALIDATE_EMAIL);

        if ($isEmail)
            $otp = UserOtpService::sendEmailOtp($user);
        else
            $otp = UserOtpService::sendPhoneOtp($user);


        return response()->json([
            "message" => trans("messages.otp sent successfully"),
            "user" => $user
        ], 201);
    }

    public function verifyOtp(VerifyRegisterOtp $request)
    {
        // $user = User::findOrFail($request->user_id);
        $user = User::where("email", $request->email_or_phone)
            ->orWhere("phone", $request->email_or_phone)
            ->firstOrFail();

        $verify = UserOtpService::verifyOtp($user, $request->otp_code);

        if (!$verify)
            return response()->json([
                "message" => trans("messages.otp is not correct or expired")
            ], 401);

        if ($request->type != "forget_password") {
            if ($request->type == "email_confirmation")
                $user->email_verified_at = now();
            elseif ($request->type == "phone_confirmation")
                $user->phone_verified_at = now();

            $user->save();
        }

        return response()->json([
            "message" => trans("messages.otp verified successfully")
        ], 200);
    }
}
