<?php

namespace Tests\Feature\User;

use App\Models\Supplier;
use App\Models\User;
use App\Models\UserPhone;
use App\Models\Wallet;
use App\Models\WalletTransaction;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class WalletTest extends TestCase
{
    use WithFaker;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_user_can_recharge_his_wallet()
    {


        $user = User::create([
            "name" => $this->faker->name,
            "email" => $this->faker->email,
            "email_verified_at" => now(),
            "password" => Hash::make("password")
        ]);
        $phone = UserPhone::create([
            "user_id" => $user->id,
            "number" => rand(123456789, 999999999),
            "country_code" => $this->faker->countryCode
        ]);
        $wallet = Wallet::create([
            "user_id" => $user->id
        ]);

        $supplier = Supplier::create([
            "user_id" => $user->id,
            "commercial_record_number" => "25234324244242423"

        ]);


        $this->actingAs($user);

        $response = $this->post('/api/v1/user/wallet-recharge', [
            "amount" => 300
        ]);

        $response->assertStatus(200);
    }
    public function test_user_wallet_recharge_can_success()
    {


        $user = User::create([
            "name" => $this->faker->name,
            "email" => $this->faker->email,
            "email_verified_at" => now(),
            "password" => Hash::make("password")
        ]);
        $phone = UserPhone::create([
            "user_id" => $user->id,
            "number" => rand(123456789, 999999999),
            "country_code" => $this->faker->countryCode
        ]);
        $wallet = Wallet::create([
            "user_id" => $user->id
        ]);

        $supplier = Supplier::create([
            "user_id" => $user->id,
            "commercial_record_number" => "25234324244242423"

        ]);

        $transaction = WalletTransaction::create([
            "user_id" => $user->id,
            "wallet_id" => $wallet->id,
            "number" => now(),
            "uuid" => uniqid("", true)
        ]);


        $this->actingAs($user);

        $response = $this->post("/api/v1/user/wallet-recharge/success/$transaction->uuid", []);

        $response->assertStatus(200);
    }
    public function test_user_wallet_recharge_can_cancelled()
    {


        $user = User::create([
            "name" => $this->faker->name,
            "email" => $this->faker->email,
            "email_verified_at" => now(),
            "password" => Hash::make("password")
        ]);
        $phone = UserPhone::create([
            "user_id" => $user->id,
            "number" => rand(123456789, 999999999),
            "country_code" => $this->faker->countryCode
        ]);
        $wallet = Wallet::create([
            "user_id" => $user->id
        ]);

        $supplier = Supplier::create([
            "user_id" => $user->id,
            "commercial_record_number" => "25234324244242423"

        ]);

        $transaction = WalletTransaction::create([
            "user_id" => $user->id,
            "wallet_id" => $wallet->id,
            "number" => now(),
            "uuid" => uniqid("", true)

        ]);


        $this->actingAs($user);

        $response = $this->post("/api/v1/user/wallet-recharge/success/$transaction->uuid", []);

        $response->assertStatus(200);
    }

    public function test_user_wallet_recharge_can_declined()
    {


        $user = User::create([
            "name" => $this->faker->name,
            "email" => $this->faker->email,
            "email_verified_at" => now(),
            "password" => Hash::make("password")
        ]);
        $phone = UserPhone::create([
            "user_id" => $user->id,
            "number" => rand(123456789, 999999999),
            "country_code" => $this->faker->countryCode
        ]);
        $wallet = Wallet::create([
            "user_id" => $user->id
        ]);

        $supplier = Supplier::create([
            "user_id" => $user->id,
            "commercial_record_number" => "25234324244242423"

        ]);

        $transaction = WalletTransaction::create([
            "user_id" => $user->id,
            "wallet_id" => $wallet->id,
            "number" => now(),
            "uuid" => uniqid("", true)

        ]);


        $this->actingAs($user);

        $response = $this->post("/api/v1/user/wallet-recharge/success/$transaction->uuid", []);

        $response->assertStatus(200);
    }
}
