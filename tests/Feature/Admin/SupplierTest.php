<?php

namespace Tests\Feature\Admin;

use App\Models\Admin;
use App\Models\Supplier;
use App\Models\User;
use App\Models\UserPhone;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class SupplierTest extends TestCase
{
    use WithFaker;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_admin_can_get_all_suppliers()
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

        $response = $this->get('/api/v1/admin/suppliers');

        $response->assertStatus(200);
    }

    public function test_admin_can_accept_suppliers()
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

        $supplierUser = User::create([
            "name" => $this->faker->name,
            "email" => $this->faker->email,
            "email_verified_at" => now(),
            "password" => Hash::make("password")
        ]);
        $supplierPhone = UserPhone::create([
            "user_id" => $supplierUser->id,
            "number" => rand(123456789, 999999999),
            "country_code" => rand(1, 999)
        ]);
        $supplier = Supplier::create([
            "user_id" => $supplierUser->id,
            "commercial_record_number" => rand(1234567, 99999999)

        ]);

        $response = $this->put("/api/v1/admin/suppliers/$supplier->id/accept");

        $response->assertStatus(200);
    }
}
