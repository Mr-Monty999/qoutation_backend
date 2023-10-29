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
        $user = User::findOrFail($request->user_id);

        if ($request->send_via == "email")
            $otp = UserOtpService::sendEmailOtp($user);
        elseif ($request->send_via == "phone")
            $otp = UserOtpService::sendPhoneOtp($user);


        return response()->json([], 201);
    }

    public function verifyOtp(VerifyRegisterOtp $request)
    {
        $user = User::findOrFail($request->user_id);
        $verify = UserOtpService::verifyOtp($user, $request->otp_code);

        if (!$verify)
            return response()->json([], 403);

        if ($request->type == "email_confirmation")
            $user->email_verified_at = now();
        elseif ($request->type == "phone_confirmation")
            $user->phone_verified_at = now();

        $user->save();

        return response()->json([], 200);
    }
}
