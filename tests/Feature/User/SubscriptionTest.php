<?php

namespace Tests\Feature\User;

use App\Models\Package;
use App\Models\Supplier;
use App\Models\User;
use App\Models\Wallet;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class SubscriptionTest extends TestCase
{
    use WithFaker;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_user_can_subscribe_in_a_package()
    {

        $user = User::create([
            "name" => "test",
            "email" => "testtesttest@example.com",
            "phone" => "96624241242",
            "email_verified_at" => now(),
            "password" => Hash::make("password"),
        ]);

        Wallet::create([
            "user_id" => $user->id,
            "balance" => 123456789
        ]);

        Supplier::create([
            "user_id" => $user->id,
            "commercial_record_number" => $this->faker->numberBetween(12344, 1234567),
            "accepted_at" => now()
        ]);

        $this->actingAs($user);

        $package =  Package::create([
            "name" => $this->faker->name,
            "days" => $this->faker->randomNumber(1, 999),
            "price" => $this->faker->randomNumber(1, 999),
            "description" => $this->faker->text
        ]);

        $response = $this->post('/api/v1/user/supplier/packages/subscribe', [
            "package_id" => $package->id
        ]);

        $response->assertStatus(200);

        $response = $this->post('/api/v1/user/supplier/packages/subscribe', [
            "package_id" => $package->id
        ]);

        $response->assertStatus(403);
    }
}
