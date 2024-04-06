<?php

namespace Tests\Feature\User;

use App\Models\Supplier;
use App\Models\User;
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
    public function test_supplier_can_subscribe_in_a_package()
    {

        $user = User::create([
            "name" => "test",
            "email" => "testtesttest@example.com",
            "phone" => "96624241242",
            "email_verified_at" => now(),
            "password" => Hash::make("password")
        ]);

        $supplier = Supplier::create([
            "user_id" => $user->id
        ]);

        $this->actingAs($user);

        $response = $this->post('/api/v1/user/supplier/subscribe');

        $response->assertStatus(200);
    }
}
