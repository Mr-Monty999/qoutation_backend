<?php

namespace Tests\Feature\User;

use App\Models\Activity;
use App\Models\Buyer;
use App\Models\City;
use App\Models\Country;
use App\Models\Neighbourhood;
use App\Models\Quotation;
use App\Models\Supplier;
use App\Models\User;
use App\Models\UserPhone;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class QuotationTest extends TestCase
{
    use WithFaker;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_user_can_create_quotation()
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
            "country_code" => rand(1, 999)
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

        $country = Country::create([
            "name" => $this->faker->country,
            "code" => "966"
        ]);

        $city = City::create([
            "name" => $this->faker->city,
            "country_id" => $country->id
        ]);
        $neighbourhood = Neighbourhood::create([
            "city_id" => $city->id,
            "name" => $this->faker->streetAddress
        ]);

        $this->actingAs($user);

        $response = $this->post('/api/v1/user/quotations', [
            "title" => $this->faker->title,
            "description" => $this->faker->text,
            "activity_ids" => Activity::pluck('id')->toArray(),
            "country_id" => $country->id,
            "city_id" => $city->id,
            "neighbourhood_id" => $neighbourhood->id,
            "products" => [
                [
                    "name" => "product1",
                    "quantity" => 333,
                    "description" => "test"
                ],
                [
                    "name" => "product2",
                    "quantity" => 1234,
                    "description" => "test"
                ]
            ]
        ]);

        $response->assertStatus(201);
    }

    public function test_user_can_update_his_quotation()
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
            "country_code" => rand(1, 999)
        ]);
        $buyer = Buyer::create([
            "user_id" => $user->id
        ]);

        $quotation = Quotation::create([
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


        $country = Country::create([
            "name" => $this->faker->country,
            "code" => "966"
        ]);

        $city = City::create([
            "name" => $this->faker->city,
            "country_id" => $country->id
        ]);
        $neighbourhood = Neighbourhood::create([
            "city_id" => $city->id,
            "name" => $this->faker->streetAddress
        ]);

        $this->actingAs($user);

        $response = $this->put("/api/v1/user/quotations/$quotation->id", [
            "title" => $this->faker->title,
            "description" => $this->faker->text,
            "activity_ids" => Activity::pluck('id')->toArray(),
            "country_id" => $country->id,
            "city_id" => $city->id,
            "neighbourhood_id" => $neighbourhood->id,
        ]);

        $response->assertStatus(200);
    }

    public function test_user_can_get_all_quotations()
    {


        $user = User::create([
            "name" => "test",
            "email" => "testtesttest@example.com",
            "phone" => "96624241242",
            "email_verified_at" => now(),
            "password" => Hash::make("password")
        ]);


        $this->actingAs($user);

        $response = $this->get('/api/v1/user/quotations');

        $response->assertStatus(200);
    }


    public function test_user_can_get_his_own_quotation_requests()
    {


        $user = User::create([
            "name" => "test",
            "email" => "testtesttest@example.com",
            "phone" => "96624241242",
            "email_verified_at" => now(),
            "password" => Hash::make("password")
        ]);


        $this->actingAs($user);

        $response = $this->get('/api/v1/user/quotations/sent-requests');

        $response->assertStatus(200);
    }

    public function test_user_can_get_show_any_quotation()
    {


        $user = User::create([
            "name" => "test",
            "email" => "testtesttest@example.com",
            "phone" => "96624241242",
            "email_verified_at" => now(),
            "password" => Hash::make("password")
        ]);


        $this->actingAs($user);

        $quotation = Quotation::create([
            "user_id" => $user->id,
            "title" => $this->faker->title,
            "description" => $this->faker->text,
        ]);

        $a1 = Activity::create([
            "name" => "a1"
        ]);

        $quotation->activities()->attach($a1->id);

        $response = $this->get("/api/v1/user/quotations/$quotation->id");

        $response->assertStatus(200);
    }

    public function test_user_can_get_delete_quotations()
    {


        $user = User::create([
            "name" => "test",
            "email" => "testtesttest@example.com",
            "phone" => "96624241242",
            "email_verified_at" => now(),
            "password" => Hash::make("password")
        ]);


        $this->actingAs($user);

        $quotation = Quotation::create([
            "user_id" => $user->id,
            "title" => $this->faker->title,
            "description" => $this->faker->text,
        ]);

        $a1 = Activity::create([
            "name" => "a1"
        ]);

        $quotation->activities()->attach($a1->id);

        $response = $this->delete("/api/v1/user/quotations/$quotation->id");

        $response->assertStatus(204);
    }

    public function test_user_can_get_sent_quotations_replies()
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
            "country_code" => rand(1, 999)
        ]);
        $supplier = Supplier::create([
            "user_id" => $user->id,
            "commercial_record_number" => $this->faker->creditCardNumber,
            "accepted_at" => now()

        ]);

        $this->actingAs($user);

        $response = $this->get('/api/v1/user/quotations/sent-replies');

        $response->assertStatus(200);
    }

    public function test_user_can_mark_his_quotation_as_completed()
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
            "country_code" => rand(1, 999)
        ]);
        $buyer = Buyer::create([
            "user_id" => $user->id
        ]);

        $quotation = Quotation::create([
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

        $response = $this->put("/api/v1/user/quotations/$quotation->id/complete", []);

        $response->assertStatus(200);
    }
}
