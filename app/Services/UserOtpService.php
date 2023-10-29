<?php

namespace App\Services;

use App\Models\User;
use App\Models\UserOtp;

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
    public static function sendEmailOtp($user, $time = 5)
    {
        $otp = UserOtpService::store($user, $time);
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
            if ($otp->code === $otpCode) {
                $otp->verified_at = now();
                $otp->save();
                return true;
            }
        }

        return false;
    }
}
