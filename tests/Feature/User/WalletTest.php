<?php

namespace Tests\Feature\User;

use App\Models\Supplier;
use App\Models\User;
use App\Models\Wallet;
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
            "phone" => $this->faker->phoneNumber,
            "email_verified_at" => now(),
            "password" => Hash::make("password")
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
}
