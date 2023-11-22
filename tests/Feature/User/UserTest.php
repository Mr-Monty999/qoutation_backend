<?php

namespace Tests\Feature\User;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class UserTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_logged_in_user_can_get_his_data()
    {


        $user = User::create([
            "name" => "test",
            "email" => "test@example.com",
            "phone" => "96624241242",
            "email_verified_at" => now(),
            "password" => Hash::make("password")
        ]);

        $this->actingAs($user);

        $response = $this->get('/api/v1/user/get-auth-user');

        $response->assertStatus(200);
    }
}
