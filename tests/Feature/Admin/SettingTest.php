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
            "website_name" => $this->faker->name,
            "home_page_text_1" => $this->faker->name,
            "home_page_text_2" => $this->faker->name,
            "home_page_text_3" => $this->faker->name,
            "home_page_text_4" => $this->faker->name,
            "home_page_text_5" => $this->faker->name,
            "home_page_text_6" => $this->faker->name,
            "home_page_button_1_text" => $this->faker->name,
            "home_page_button_1_url" => $this->faker->name,
            "home_page_feature_1_text" => $this->faker->name,
            "home_page_feature_2_text" => $this->faker->name,
            "home_page_feature_3_text" => $this->faker->name,
            "home_page_feature_4_text" => $this->faker->name,
            "landing_page_footer_text_1" => $this->faker->name,

        ]);

        $response->assertStatus(200);
    }
}
