<?php

namespace Tests\Feature\User;

use App\Models\Supplier;
use App\Models\User;
use App\Models\UserOtp;
use App\Services\UserOtpService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class UserAuthTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_user_can_login()
    {
        $user = User::create([
            "name" => "test",
            "email" => "test@example.com",
            "phone" => "96624241242",
            "email_verified_at" => now(),
            "password" => Hash::make("password")
        ]);
        $supplier = Supplier::create([
            "user_id" => $user->id
        ]);

        $response = $this->post('/api/v1/auth/login', [
            "email" => "test@example.com",
            "password" => "password",
        ]);

        $response->assertStatus(200);
    }

    public function test_user_can_register_buyer()
    {

        $response = $this->post('/api/v1/auth/register/buyer', [
            "email" => "test@example.com",
            "password" => "password",
            "password_confirmation" => "password",
            "name" => "test",
            "country_code" => "966",
            "phone" => "123456789"
        ]);

        $response->assertStatus(201);
    }
    public function test_user_can_register_supplier()
    {

        $response = $this->post('/api/v1/auth/register/supplier', [
            "email" => "test@example.com",
            "password" => "password",
            "password_confirmation" => "password",
            "name" => "test",
            "country_code" => "966",
            "phone" => "123456789"
        ]);

        $response->assertStatus(201);
    }

    public function test_user_can_send_otp()
    {


        $user = User::create([
            "name" => "test",
            "email" => "test@example.com",
            "phone" => "96624241242",
            "email_verified_at" => now(),
            "password" => Hash::make("password")
        ]);

        $response = $this->post('/api/v1/auth/send-otp', [
            "email_or_phone" => $user->email
        ]);

        $response->assertStatus(201);
    }

    public function test_user_can_verify_otp()
    {


        $user = User::create([
            "name" => "test",
            "email" => "test@example.com",
            "phone" => "96624241242",
            "email_verified_at" => now(),
            "password" => Hash::make("password")
        ]);

        $otp = UserOtpService::sendEmailOtp($user);



        $response = $this->post('/api/v1/auth/verify-otp', [
            "email_or_phone" => $user->email,
            "otp_code" => $otp->code . "",
            "type" => "email_confirmation"

        ]);

        $response->assertStatus(200);
    }

    public function test_user_can_reset_password()
    {


        $user = User::create([
            "name" => "test",
            "email" => "test@example.com",
            "phone" => "96624241242",
            "email_verified_at" => now(),
            "password" => Hash::make("password")
        ]);

        $otp = UserOtp::create([
            "user_id" => $user->id,
            "code" => rand(1234, 9999),
            "expired_at" => now()->addMinutes(5)
        ]);

        $verifyOtp = UserOtpService::verifyOtp($user, $otp->code);






        $response = $this->post('/api/v1/auth/reset-password', [
            "otp_code" => $otp->code . "",
            "user_id" => $user->id . "",
            "password" => "password",
            "password_confirmation" => "password"

        ]);

        $response->assertStatus(200);
    }
}
