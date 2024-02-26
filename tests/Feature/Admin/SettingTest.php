<?php

namespace Tests\Feature\Admin;

use App\Models\Admin;
use App\Models\Setting;
use App\Models\User;
use App\Models\UserPhone;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class SettingTest extends TestCase
{
    use WithFaker;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_admin_can_get_all_settings()
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

        Setting::create([
            "key" => $this->faker->name,
            "value" => $this->faker->name,
            "description" => $this->faker->text
        ]);

        $response = $this->get('/api/v1/admin/settings');

        $response->assertStatus(200);
    }


    public function test_admin_can_update_settings()
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

        $setting = Setting::create([
            "key" => $this->faker->name,
            "value" => $this->faker->name,
            "description" => $this->faker->text
        ]);

        $response = $this->put("/api/v1/admin/settings", [
            "settings" => [
                [
                    "key" => "website_name",
                    "value" => $this->faker->name,
                    "description" => $this->faker->text
                ],
                [
                    "key" => "website_name",
                    "value" => $this->faker->name,
                    "description" => $this->faker->text
                ],
            ]

        ]);

        $response->assertStatus(200);
    }
}
