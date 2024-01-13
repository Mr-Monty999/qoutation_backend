<?php

namespace Tests\Feature\Public;

use App\Models\City;
use App\Models\Country;
use App\Models\Neighbourhood;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class PublicApiTest extends TestCase
{
    use WithFaker;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_anyone_can_get_activities()
    {

        $response = $this->get('/api/v1/public/activities');

        $response->assertStatus(200);
    }
    public function test_anyone_can_get_all_countries()
    {

        $response = $this->get('/api/v1/public/countries');

        $response->assertStatus(200);
    }
    public function test_anyone_can_get_country_cities()
    {

        $country = Country::create([
            "name" => $this->faker->country,
            "code" => "966"
        ]);

        $city = City::create([
            "name" => $this->faker->city,
            "country_id" => $country->id
        ]);

        $response = $this->get("/api/v1/public/countries/$country->id/cities");

        $response->assertStatus(200);
    }
    public function test_anyone_can_get_city_neighbourhoods()
    {

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
            "name" => $this->faker->streetName
        ]);

        $response = $this->get("/api/v1/public/cities/$city->id/neighbourhoods");



        $response->assertStatus(200);
    }
}
