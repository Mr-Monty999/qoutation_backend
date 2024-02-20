<?php

namespace Tests\Feature\Admin;

use App\Models\Admin;
use App\Models\Country;
use App\Models\User;
use App\Models\UserPhone;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class CountryTest extends TestCase
{
    use WithFaker;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_admin_can_get_all_countries()
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
        $admin = Admin::create([
            "user_id" => $user->id
        ]);
        $this->actingAs($user);

        Country::create([
            "name" => $this->faker->name,
            "code" => $this->faker->randomNumber(1, 999),
            "description" => $this->faker->text
        ]);

        $response = $this->get('/api/v1/admin/countries');

        $response->assertStatus(200);
    }
    public function test_admin_can_store_country()
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
        $admin = Admin::create([
            "user_id" => $user->id
        ]);
        $this->actingAs($user);

        $response = $this->post('/api/v1/admin/countries', [
            "name" => $this->faker->name,
            "code" => $this->faker->randomNumber(1, 999),
            "description" => $this->faker->text

        ]);

        $response->assertStatus(201);
    }

    public function test_admin_can_update_country()
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
        $admin = Admin::create([
            "user_id" => $user->id
        ]);
        $this->actingAs($user);

        $country = Country::create([
            "name" => $this->faker->name,
            "code" => $this->faker->randomNumber(1, 999),
            "description" => $this->faker->text
        ]);

        $response = $this->put("/api/v1/admin/countries/$country->id", [
            "name" => $this->faker->name,
            "code" => $this->faker->randomNumber(1, 999),
            "description" => $this->faker->text
        ]);

        $response->assertStatus(200);
    }

    public function test_admin_can_show_country()
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
        $admin = Admin::create([
            "user_id" => $user->id
        ]);
        $this->actingAs($user);

        $country = Country::create([
            "name" => $this->faker->name,
            "code" => $this->faker->randomNumber(1, 999),
            "description" => $this->faker->text
        ]);

        $response = $this->get("/api/v1/admin/countries/$country->id");

        $response->assertStatus(200);
    }

    public function test_admin_can_delete_country()
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
        $admin = Admin::create([
            "user_id" => $user->id
        ]);
        $this->actingAs($user);

        $country = Country::create([
            "name" => $this->faker->name,
            "code" => $this->faker->randomNumber(1, 999),
            "description" => $this->faker->text
        ]);

        $response = $this->delete("/api/v1/admin/countries/$country->id");

        $response->assertStatus(200);
    }
}
