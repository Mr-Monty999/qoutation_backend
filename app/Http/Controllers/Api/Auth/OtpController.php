<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Auth\SendRegisterOtp;
use App\Http\Requests\Api\Auth\VerifyRegisterOtp;
use App\Mail\EmailConfirmationMail;
use App\Models\User;
use App\Services\UserOtpService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\ValidationException;

class OtpController extends Controller
{
    public function sendOtp(SendRegisterOtp $request)
    {

        $user = User::where("email", $request->email_or_phone)
            ->orWhereHas("phone", function ($q) use ($request) {
                $q->where("number", $request->email_or_phone);
            })
            ->first();

        if ($request->type == "reset_password") {
            if (!$user)
                throw ValidationException::withMessages(['email_or_phone' => trans("messages.account not found")]);
        } else if ($request->type == "update_email") {
            if ($user)
                throw ValidationException::withMessages(['email_or_phone' => trans("messages.email exists")]);
        }

        $isEmail = filter_var($request->email_or_phone, FILTER_VALIDATE_EMAIL);

        if ($isEmail) {
            $otp = UserOtpService::sendEmailOtp($request->email_or_phone, $request->type);
        } else
            $otp = UserOtpService::sendPhoneOtp($request->email_or_phone, $request->type);


        return response()->json([
            "message" => trans("messages.otp sent successfully"),
            // "user" => $user
        ], 201);
    }

    public function verifyOtp(VerifyRegisterOtp $request)
    {

        $verify = UserOtpService::verifyOtp($request->email_or_phone, $request->otp_code);

        if (!$verify)
            return response()->json([
                "message" => trans("messages.otp is not correct or expired")
            ], 401);

        return response()->json([
            "message" => trans("messages.otp verified successfully")
        ], 200);
    }
}
