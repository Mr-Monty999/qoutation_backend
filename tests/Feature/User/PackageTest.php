<?php

namespace Tests\Feature\User;

use App\Models\Package;
use App\Models\Supplier;
use App\Models\User;
use App\Models\UserPhone;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class PackageTest extends TestCase
{
    use WithFaker;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_user_can_list_all_packages()
    {
        $user = User::create([
            "name" => $this->faker->name,
            "email" => $this->faker->email,
            "email_verified_at" => now(),
            "password" => Hash::make("password")
        ]);
        UserPhone::create([
            "user_id" => $user->id,
            "number" => rand(123456789, 999999999),
            "country_code" => rand(1, 999)
        ]);

        Supplier::create([
            "user_id" => $user->id,
            "commercial_record_number" => $this->faker->numberBetween(12344, 1234567),
            "accepted_at" => now()
        ]);


        $this->actingAs($user);

        Package::create([
            "name" => $this->faker->name,
            "days" => $this->faker->randomNumber(1, 999),
            "price" => $this->faker->randomNumber(1, 999),
            "description" => $this->faker->text
        ]);

        $response = $this->get('/api/v1/user/packages');

        $response->assertStatus(200);
    }
}
