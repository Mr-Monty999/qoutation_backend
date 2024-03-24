<?php

namespace Tests\Feature\User;

use App\Models\Activity;
use App\Models\Buyer;
use App\Models\Service;
use App\Models\ServiceReply;
use App\Models\Supplier;
use App\Models\User;
use App\Models\UserPhone;
use App\Models\Wallet;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class ServiceReplyTest extends TestCase
{
    use WithFaker;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_supplier_can_send_service_reply()
    {
        $user = User::create([
            "name" => "test",
            "email" => "testtesttest@example.com",
            "phone" => "96624241242",
            "email_verified_at" => now(),
            "password" => Hash::make("password")
        ]);

        $phone = UserPhone::create([
            "user_id" => $user->id,
            "number" => $this->faker->numberBetween(123456789, 999999999),
            "country_code" => $this->faker->numberBetween(1, 300)

        ]);

        $wallet = Wallet::create([
            "user_id" => $user->id,
            "balance" => env("SUPPLIER_QUOTATION_PRICE")
        ]);

        $supplier = Supplier::create([
            "user_id" => $user->id,
            "commercial_record_number" => rand(1234567, 99999999),
            "accepted_at" => now()

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


        $response = $this->post("/api/v1/user/services/$service->id/replies", [
            "title" => $this->faker->title,
            "description" => $this->faker->text,
            "price" => $this->faker->randomNumber(1, 4300)
        ]);

        $response->assertStatus(201);
    }

    public function test_supplier_can_edit_his_service_reply()
    {
        $user = User::create([
            "name" => "test",
            "email" => "testtesttest@example.com",
            "phone" => "96624241242",
            "email_verified_at" => now(),
            "password" => Hash::make("password")
        ]);

        $phone = UserPhone::create([
            "user_id" => $user->id,
            "number" => $this->faker->numberBetween(123456789, 999999999),
            "country_code" => $this->faker->numberBetween(1, 300)

        ]);

        $wallet = Wallet::create([
            "user_id" => $user->id,
            "balance" => env("SUPPLIER_QUOTATION_PRICE")
        ]);

        $supplier = Supplier::create([
            "user_id" => $user->id,
            "commercial_record_number" => rand(1234567, 99999999),
            "accepted_at" => now()

        ]);

        $this->actingAs($user);



        $service = Service::create([
            "user_id" => $user->id,
            "title" => $this->faker->title,
            "description" => $this->faker->text,
        ]);

        $serviceReply = ServiceReply::create([
            "user_id" => $user->id,
            "service_id" => $service->id,
            "price" => $this->faker->randomDigit(1, 3340),
            "title" => $this->faker->title,
            "description" => $this->faker->text,
        ]);

        $a1 = Activity::create([
            "name" => "a1"
        ]);

        $service->activities()->attach($a1->id);


        $response = $this->put("/api/v1/user/services/$service->id/replies/$serviceReply->id", [
            "title" => $this->faker->title,
            "description" => $this->faker->text,
            "price" => $this->faker->randomNumber(1, 4300)
        ]);

        $response->assertStatus(200);
    }

    public function test_user_can_accept_service_reply()
    {
        $user = User::create([
            "name" => "test",
            "email" => "testtesttest@example.com",
            "phone" => "96624241242",
            "email_verified_at" => now(),
            "password" => Hash::make("password")
        ]);

        $phone = UserPhone::create([
            "user_id" => $user->id,
            "number" => $this->faker->numberBetween(123456789, 999999999),
            "country_code" => $this->faker->numberBetween(1, 300)

        ]);

        $this->actingAs($user);

        $wallet = Wallet::create([
            "user_id" => $user->id,
            "balance" => env("SUPPLIER_QUOTATION_PRICE")
        ]);

        $buyer = Buyer::create([
            "user_id" => $user->id,
            // "commercial_record_number" => rand(1234567, 99999999)
        ]);

        $service = Service::create([
            "user_id" => $user->id,
            "title" => $this->faker->title,
            "description" => $this->faker->text,
        ]);

        $a1 = Activity::create([
            "name" => "a1"
        ]);

        $service->activities()->attach($a1->id);


        $serviceReply = ServiceReply::create([
            "service_id" => $service->id,
            "user_id" => $user->id,
            "price" => $this->faker->numberBetween(1, 12342)
        ]);

        $response = $this->put("/api/v1/user/services/$service->id/replies/$serviceReply->id/accept");

        $response->assertStatus(200);
    }

    public function test_user_can_undo_accept_service_reply()
    {
        $user = User::create([
            "name" => "test",
            "email" => "testtesttest@example.com",
            "phone" => "96624241242",
            "email_verified_at" => now(),
            "password" => Hash::make("password")
        ]);

        $phone = UserPhone::create([
            "user_id" => $user->id,
            "number" => $this->faker->numberBetween(123456789, 999999999),
            "country_code" => $this->faker->numberBetween(1, 300)

        ]);

        $this->actingAs($user);

        $wallet = Wallet::create([
            "user_id" => $user->id,
            "balance" => env("SUPPLIER_QUOTATION_PRICE")
        ]);

        $buyer = Buyer::create([
            "user_id" => $user->id,
            // "commercial_record_number" => rand(1234567, 99999999)
        ]);

        $service = Service::create([
            "user_id" => $user->id,
            "title" => $this->faker->title,
            "description" => $this->faker->text,
        ]);

        $a1 = Activity::create([
            "name" => "a1"
        ]);

        $service->activities()->attach($a1->id);



        $serviceReply = ServiceReply::create([
            "service_id" => $service->id,
            "user_id" => $user->id,
            "price" => $this->faker->numberBetween(1, 12342),
            "accepted_by" => $user->id
        ]);

        $response = $this->put("/api/v1/user/services/$service->id/replies/$serviceReply->id/unaccept");

        $response->assertStatus(200);
    }
}
