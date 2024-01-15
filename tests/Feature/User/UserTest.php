<?php

namespace Tests\Feature\User;

use App\Models\User;
use App\Models\Wallet;
use App\Services\UserOtpService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class UserTest extends TestCase
{
    use WithFaker;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_logged_in_user_can_get_his_data()
    {


        $user = User::create([
            "name" => $this->faker->name,
            "email" => $this->faker->email,
            "phone" => $this->faker->phoneNumber,
            "email_verified_at" => now(),
            "password" => Hash::make("password")
        ]);

        $this->actingAs($user);

        $response = $this->get('/api/v1/user/get-auth-user');

        $response->assertStatus(200);
    }

    public function test_user_can_get_info()
    {
        $user = User::create([
            "name" => $this->faker->name,
            "email" => $this->faker->email,
            "phone" => $this->faker->phoneNumber,
            "email_verified_at" => now(),
            "password" => Hash::make("password")
        ]);

        $wallet = Wallet::create([
            "user_id" => $user->id,
            "balance" => env("SUPPLIER_QUOTATION_PRICE")
        ]);



        $this->actingAs($user);

        $response = $this->get("/api/v1/user/info");

        $response->assertStatus(200);
    }

    public function test_user_can_update_his_password()
    {
        $user = User::create([
            "name" => $this->faker->name,
            "email" => $this->faker->email,
            "phone" => $this->faker->phoneNumber,
            "email_verified_at" => now(),
            "password" => Hash::make("password")
        ]);

        $wallet = Wallet::create([
            "user_id" => $user->id,
            "balance" => env("SUPPLIER_QUOTATION_PRICE")
        ]);


        $this->actingAs($user);

        $response = $this->put('/api/v1/user/password/update', [
            "old_password" => "password",
            "new_password" => "password",
            "new_password_confirmation" => "password"
        ]);

        $response->assertStatus(200);
    }
    public function test_user_can_update_his_email()
    {
        $user = User::create([
            "name" => $this->faker->name,
            "email" => $this->faker->email,
            "phone" => $this->faker->phoneNumber,
            "email_verified_at" => now(),
            "password" => Hash::make("password")
        ]);

        $wallet = Wallet::create([
            "user_id" => $user->id,
            "balance" => env("SUPPLIER_QUOTATION_PRICE")
        ]);

        $otp = UserOtpService::sendEmailOtp($user, "email_confirmation");


        $this->actingAs($user);

        $response = $this->put('/api/v1/user/email/update', [
            "otp" => $otp->code . "",
            "new_email" => $this->faker->email
        ]);

        $response->assertStatus(200);
    }
}
