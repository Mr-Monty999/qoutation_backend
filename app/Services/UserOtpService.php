<?php

namespace App\Services;

use App\Jobs\EmailJob;
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

    public static function store($identifier, $time = 5)
    {
        $otp = UserOtp::create([
            "identifier" => $identifier,
            "code" => rand(1234, 9999),
            "expired_at" => now()->addMinutes($time)
        ]);

        return $otp;
    }
    public static function sendEmailOtp($identifier, $otpType, $time = 5)
    {
        $otp = UserOtpService::store($identifier, $time);
        if ($otpType == "email_confirmation" || $otpType == "update_email") {
            EmailJob::dispatch([
                "type" => "email_confirmation_otp",
                "identifier" => $identifier,
                "otp" => $otp
            ]);
        } elseif ($otpType == "reset_password")
            EmailJob::dispatch([
                "type" => "reset_password_otp",
                "identifier" => $identifier,
                "otp" => $otp
            ]);

        return $otp;
    }
    public static function sendPhoneOtp($identifier, $time = 5)
    {
        $otp = UserOtpService::store($identifier, $time);
        return $otp;
    }

    public static function verifyOtp($identifier, $otpCode)
    {

        $otp = UserOtp::where("identifier", $identifier)->latest()->firstOrFail();

        if ($otp->expired_at > now() && $otp->verified_at == null) {
            if ($otp->code == $otpCode) {
                $otp->verified_at = now();
                $otp->save();
                return true;
            }
        }

        return false;
    }
    public static function checkOtpIsVerified($identifier, $otpCode)
    {
        $otp = UserOtp::where("identifier", $identifier)->latest()->firstOrFail();

        if ($otp->expired_at > now() && $otp->verified_at != null)
            if ($otp->code == $otpCode)
                return true;


        return false;
    }
}
