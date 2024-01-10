<?php

namespace Tests\Feature\User;

use App\Models\Activity;
use App\Models\Country;
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
    use WithFaker;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_user_can_login()
    {
        $user = User::create([
            "name" => $this->faker->name,
            "email" => $this->faker->email,
            "phone" => $this->faker->phoneNumber,
            "email_verified_at" => now(),
            "password" => Hash::make("password")
        ]);
        $supplier = Supplier::create([
            "user_id" => $user->id,
            "commercial_record_number" => "1432424",

        ]);

        $response = $this->post('/api/v1/auth/login', [
            "email" => $user->email,
            "password" => "password",
        ]);

        $response->assertStatus(200);
    }

    public function test_user_can_register_buyer()
    {

        $country = Country::create([
            "name" => "country",
            "code" => "966"
        ]);

        Activity::factory(10)->create();

        $acitivties = Activity::pluck("id")->toArray();



        $response = $this->post('/api/v1/auth/register/buyer', [
            "email" => $this->faker->email,
            "password" => "password",
            "password_confirmation" => "password",
            "name" => $this->faker->name,
            "country_id" => $country->id,
            "phone" => rand(123456789, 99999999),
            "lat" => "134242442.2344",
            "lng" => "242424242.242",
            "address" => "riydh - street"


        ]);

        $response->assertStatus(201);
    }
    public function test_user_can_register_supplier()
    {

        $country = Country::create([
            "name" => "country",
            "code" => "966"
        ]);

        Activity::factory(10)->create();

        $acitivties = Activity::pluck("id")->toArray();

        $response = $this->post('/api/v1/auth/register/supplier', [
            "email" => $this->faker->email,
            "password" => "password",
            "password_confirmation" => "password",
            "name" => $this->faker->name,
            "country_id" => $country->id,
            "phone" => rand(123456789, 99999999),
            "activity_ids" => $acitivties,
            "commercial_record_number" => "1432424",
            "lat" => "134242442.2344",
            "lng" => "242424242.242",
            "activity_description" => "test test",
            "address" => "riydh - street"
        ]);

        $response->assertStatus(201);
    }

    public function test_user_can_send_otp()
    {


        $user = User::create([
            "name" => $this->faker->name,
            "email" => $this->faker->email,
            "phone" => $this->faker->phoneNumber,
            "email_verified_at" => now(),
            "password" => Hash::make("password")
        ]);

        $response = $this->post('/api/v1/auth/send-otp', [
            "email_or_phone" => $user->email,
            "type" => "email_confirmation"


        ]);

        $response->assertStatus(201);
    }

    public function test_user_can_verify_otp()
    {


        $user = User::create([
            "name" => $this->faker->name,
            "email" => $this->faker->email,
            "phone" => $this->faker->phoneNumber,
            "email_verified_at" => now(),
            "password" => Hash::make("password")
        ]);

        $otp = UserOtpService::sendEmailOtp($user, "email_confirmation");



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
            "name" => $this->faker->name,
            "email" => $this->faker->email,
            "phone" => $this->faker->phoneNumber,
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
            "email_or_phone" => $user->email,
            "password" => "password",
            "password_confirmation" => "password"

        ]);

        $response->assertStatus(200);
    }
}
