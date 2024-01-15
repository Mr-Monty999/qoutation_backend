<?php

namespace Tests\Feature\User;

use App\Models\Buyer;
use App\Models\City;
use App\Models\Country;
use App\Models\Neighbourhood;
use App\Models\User;
use App\Models\Wallet;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class BuyerTest extends TestCase
{
    use WithFaker;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_buyer_can_update_his_profile()
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

        $buyer = Buyer::create([
            "user_id" => $user->id
        ]);

        $country = Country::create([
            "name" => $this->faker->country,
            "code" => "966"
        ]);
        $city = City::create([
            "country_id" => $country->id,
            "name" => $this->faker->city
        ]);
        $neighbourhood = Neighbourhood::create([
            "city_id" => $city->id,
            "name" => $this->faker->streetName
        ]);

        $this->actingAs($user);
        $response = $this->put('/api/v1/user/buyer/profile/update', [
            "name" => $this->faker->name,
            "phone" => $this->faker->numberBetween(123456789, 999999999),
            "birthdate" => $this->faker->date,
            "country_id" => $country->id,
            "city_id" => $city->id,
            "neighbourhood_id" => $neighbourhood->id,
            "lat" => $this->faker->latitude . "",
            "lng" => $this->faker->longitude . "",
            "address" => $this->faker->address
        ]);

        $response->assertStatus(200);
    }
}
