<?php

namespace Tests\Feature\Admin;

use App\Models\Admin;
use App\Models\User;
use App\Models\UserPhone;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AdminProfileTest extends TestCase
{
    use WithFaker;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_admin_can_update_his_profile()
    {

        $user = User::create([
            "name" => $this->faker->name,
            "email" => $this->faker->email,
            "email_verified_at" => now(),
            "password" => Hash::make("password")
        ]);

        $admin = Admin::create([
            "user_id" => $user->id
        ]);

        $phone = UserPhone::create([
            "user_id" => $user->id,
            "number" => rand(123456789, 999999999),
            "country_code" => "966"
        ]);

        $this->actingAs($user);
        $response = $this->put('/api/v1/admin/profile', [
            "name" => $this->faker->name,
            "phone" => $phone->number,
            "lat" => $this->faker->latitude . "",
            "lng" => $this->faker->longitude . "",
        ]);

        $response->assertStatus(200);
    }
}
