<?php

namespace Tests\Feature\Admin;

use App\Models\Activity;
use App\Models\Admin;
use App\Models\User;
use App\Models\UserPhone;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class ActivityTest extends TestCase
{
    use WithFaker;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_admin_can_get_all_activities()
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

        Activity::create([
            "name" => $this->faker->name
        ]);

        $response = $this->get('/api/v1/admin/activities');

        $response->assertStatus(200);
    }
    public function test_admin_can_store_activity()
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

        $response = $this->post('/api/v1/admin/activities', [
            "name" => $this->faker->name
        ]);

        $response->assertStatus(201);
    }

    public function test_admin_can_update_activity()
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

        $activity = Activity::create([
            "name" => $this->faker->name
        ]);

        $response = $this->put("/api/v1/admin/activities/$activity->id", [
            "name" => $this->faker->name
        ]);

        $response->assertStatus(200);
    }

    public function test_admin_can_show_activity()
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

        $activity = Activity::create([
            "name" => $this->faker->name
        ]);

        $response = $this->get("/api/v1/admin/activities/$activity->id");

        $response->assertStatus(200);
    }

    public function test_admin_can_delete_activity()
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

        $activity = Activity::create([
            "name" => $this->faker->name
        ]);

        $response = $this->delete("/api/v1/admin/activities/$activity->id");

        $response->assertStatus(200);
    }
}
