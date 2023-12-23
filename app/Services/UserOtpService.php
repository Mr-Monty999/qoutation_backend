<?php

namespace App\Services;

use App\Mail\EmailConfirmationMail;
use App\Mail\ResetPasswordMail;
use App\Models\User;
use App\Models\UserOtp;
use Illuminate\Support\Facades\Mail;

/**
 * Class UserOtpService.
 */
class UserOtpService
{

    public static function store($user, $time = 5)
    {
        $otp = UserOtp::create([
            "user_id" => $user->id,
            "code" => rand(1234, 9999),
            "expired_at" => now()->addMinutes($time)
        ]);

        return $otp;
    }
    public static function sendEmailOtp($user, $otpType, $time = 5)
    {
        $otp = UserOtpService::store($user, $time);
        if ($otpType == "email_confirmation")
            Mail::to($user)->send(new EmailConfirmationMail($otp));
        elseif ($otpType == "reset_password")
            Mail::to($user)->send(new ResetPasswordMail($otp));

        return $otp;
    }
    public static function sendPhoneOtp($user, $time = 5)
    {
        $otp = UserOtpService::store($user, $time);
        return $otp;
    }

    public static function verifyOtp($user, $otpCode)
    {

        $otp = $user->otps()->latest()->firstOrFail();

        if ($otp->expired_at > now() && $otp->verified_at == null) {
            if ($otp->code == $otpCode) {
                $otp->verified_at = now();
                $otp->save();
                return true;
            }
        }

        return false;
    }
    public static function checkOtpIsVerified($user, $otpCode)
    {
        $otp = $user->otps()->latest()->firstOrFail();

        if ($otp->expired_at > now() && $otp->verified_at != null)
            if ($otp->code == $otpCode)
                return true;


        return false;
    }
}
