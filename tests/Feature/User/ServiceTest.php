<?php

namespace Tests\Feature\User;

use App\Models\Activity;
use App\Models\Buyer;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class ServiceTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_user_can_create_service()
    {


        $user = User::create([
            "name" => "test",
            "email" => "testtesttest@example.com",
            "phone" => "96624241242",
            "email_verified_at" => now(),
            "password" => Hash::make("password")
        ]);

        $buyer = Buyer::create([
            "user_id" => $user->id
        ]);

        $a1 = Activity::create([
            "name" => "a1"
        ]);
        $a2 = Activity::create([
            "name" => "a2"
        ]);

        $this->actingAs($user);

        $response = $this->post('/api/v1/user/services', [
            "title" => "title",
            "description" => "description",
            "activity_ids" => "$a1->id,$a2->id"
        ]);

        $response->assertStatus(201);
    }

    public function test_user_can_get_all_services()
    {


        $user = User::create([
            "name" => "test",
            "email" => "testtesttest@example.com",
            "phone" => "96624241242",
            "email_verified_at" => now(),
            "password" => Hash::make("password")
        ]);


        $this->actingAs($user);

        $response = $this->get('/api/v1/user/services');

        $response->assertStatus(200);
    }


    public function test_user_can_get_his_own_services()
    {


        $user = User::create([
            "name" => "test",
            "email" => "testtesttest@example.com",
            "phone" => "96624241242",
            "email_verified_at" => now(),
            "password" => Hash::make("password")
        ]);


        $this->actingAs($user);

        $response = $this->get('/api/v1/user/auth/services');

        $response->assertStatus(200);
    }
}
