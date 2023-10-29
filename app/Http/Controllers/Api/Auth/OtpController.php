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
    public function sendRegisterOtp(SendRegisterOtp $request)
    {
        $user = User::findOrFail($request->user_id);

        $otp = UserOtpService::sendEmailOtp($user);

        return response()->json([], 201);
    }

    public function verifyRegisterOtp(VerifyRegisterOtp $request)
    {
        $verify = UserOtpService::verifyOtp($request->user_id, $request->otp_code, "email");

        if (!$verify)
            return response()->json([], 403);

        return response()->json([], 200);
    }

    public function sendForgetPasswordOtp()
    {
    }

    public function verifyForgetPasswordOtp()
    {
    }
}
