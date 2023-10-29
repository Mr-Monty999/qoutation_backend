<?php

namespace App\Services;

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
            "expired_at" => now()->addMinutes(5)
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
}
