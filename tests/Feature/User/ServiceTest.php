<?php

namespace Tests\Feature\User;

use App\Models\Activity;
use App\Models\Buyer;
use App\Models\Service;
use App\Models\Supplier;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class ServiceTest extends TestCase
{
    use WithFaker;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_user_can_create_service()
    {


        $user = User::create([
            "name" => $this->faker->name,
            "email" => $this->faker->email,
            "phone" => $this->faker->phoneNumber,
            "email_verified_at" => now(),
            "password" => Hash::make("password")
        ]);

        $buyer = Buyer::create([
            "user_id" => $user->id
        ]);

        $a1 = Activity::create([
            "name" => $this->faker->name
        ]);
        $a2 = Activity::create([
            "name" => $this->faker->name
        ]);

        $this->actingAs($user);

        $response = $this->post('/api/v1/user/services', [
            "title" => $this->faker->title,
            "description" => $this->faker->text,
            "activity_ids" => "$a1->id,$a2->id"
        ]);

        $response->assertStatus(201);
    }

    public function test_user_can_update_his_services()
    {


        $user = User::create([
            "name" => $this->faker->name,
            "email" => $this->faker->email,
            "phone" => $this->faker->phoneNumber,
            "email_verified_at" => now(),
            "password" => Hash::make("password")
        ]);

        $buyer = Buyer::create([
            "user_id" => $user->id
        ]);

        $service = Service::create([
            "user_id" => $user->id,
            "title" => $this->faker->title,
            "description" => $this->faker->text,
        ]);

        $a1 = Activity::create([
            "name" => "a1"
        ]);
        $a2 = Activity::create([
            "name" => "a2"
        ]);

        $this->actingAs($user);

        $response = $this->put("/api/v1/user/services/$service->id", [
            "title" => $this->faker->title,
            "description" => $this->faker->text,
            "activity_ids" => "$a1->id,$a2->id"
        ]);

        $response->assertStatus(200);
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


    public function test_buyer_can_get_his_own_services()
    {


        $user = User::create([
            "name" => "test",
            "email" => "testtesttest@example.com",
            "phone" => "96624241242",
            "email_verified_at" => now(),
            "password" => Hash::make("password")
        ]);


        $this->actingAs($user);

        $response = $this->get('/api/v1/user/buyer/services');

        $response->assertStatus(200);
    }

    public function test_user_can_get_show_any_service()
    {


        $user = User::create([
            "name" => "test",
            "email" => "testtesttest@example.com",
            "phone" => "96624241242",
            "email_verified_at" => now(),
            "password" => Hash::make("password")
        ]);


        $this->actingAs($user);

        $service = Service::create([
            "user_id" => $user->id,
            "title" => $this->faker->title,
            "description" => $this->faker->text,
        ]);

        $a1 = Activity::create([
            "name" => "a1"
        ]);

        $service->activities()->attach($a1->id);

        $response = $this->get("/api/v1/user/services/$service->id");

        $response->assertStatus(200);
    }

    public function test_user_can_get_delete_services()
    {


        $user = User::create([
            "name" => "test",
            "email" => "testtesttest@example.com",
            "phone" => "96624241242",
            "email_verified_at" => now(),
            "password" => Hash::make("password")
        ]);


        $this->actingAs($user);

        $service = Service::create([
            "user_id" => $user->id,
            "title" => $this->faker->title,
            "description" => $this->faker->text,
        ]);

        $a1 = Activity::create([
            "name" => "a1"
        ]);

        $service->activities()->attach($a1->id);

        $response = $this->delete("/api/v1/user/services/$service->id");

        $response->assertStatus(204);
    }

    public function test_supplier_get_services()
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
            "commercial_record_number" => $this->faker->creditCardNumber
        ]);

        $this->actingAs($user);

        $response = $this->get('/api/v1/user/supplier/services');

        $response->assertStatus(200);
    }
}
