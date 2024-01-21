<?php

namespace Tests\Feature\Public;

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
    public function test_anyone_can_get_all_suppliers()
    {

        $user = User::create([
            "name" => $this->faker->name,
            "email" => $this->faker->email,
            "email_verified_at" => now(),
            "password" => Hash::make("password")
        ]);

        $supplier = Supplier::create([
            "user_id" => $user->id,
            "commercial_record_number" => $this->faker->creditCardNumber
        ]);

        $phone = UserPhone::create([
            "user_id" => $user->id,
            "number" => rand(123456789, 999999999),
            "country_code" => rand(1, 999)
        ]);

        $response = $this->get('/api/v1/public/suppliers');

        $response->assertStatus(200);
    }
}
