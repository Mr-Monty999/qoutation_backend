<?php

namespace Tests\Feature\User;

use App\Models\Activity;
use App\Models\Quotation;
use App\Models\QuotationProduct;
use App\Models\QuotationQuotation;
use App\Models\Supplier;
use App\Models\User;
use App\Models\UserPhone;
use App\Models\Wallet;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class QuotationQuotationTest extends TestCase
{

    use WithFaker;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    // public function test_user_can_create_quotation()
    // {


    //     $user = User::create([
    //         "name" => $this->faker->name,
    //         "email" => $this->faker->email,
    //         "email_verified_at" => now(),
    //         "password" => Hash::make("password")
    //     ]);
    //     $phone = UserPhone::create([
    //         "user_id" => $user->id,
    //         "number" => rand(123456789, 999999999),
    //         "country_code" => rand(1, 999)
    //     ]);
    //     $wallet = Wallet::create([
    //         "user_id" => $user->id,
    //         "balance" => env("SUPPLIER_QUOTATION_PRICE")
    //     ]);
    //     $supplier = Supplier::create([
    //         "user_id" => $user->id,
    //         "commercial_record_number" => "23443234324"
    //     ]);

    //     $a1 = Activity::create([
    //         "name" => $this->faker->name
    //     ]);
    //     $a2 = Activity::create([
    //         "name" => $this->faker->name
    //     ]);

    //     $this->actingAs($user);

    //     $quotation = Quotation::create([
    //         "user_id" => $user->id,
    //         "title" => $this->faker->title,
    //         "description" => $this->faker->text,
    //     ]);

    //     $response = $this->post("/api/v1/user/quotations/$quotation->id/quotations", [
    //         "title" => $this->faker->title,
    //         "description" => $this->faker->text,
    //         "amount" => 3432434
    //     ]);

    //     $response->assertStatus(201);
    // }

    // public function test_user_can_create_products_quotation()
    // {


    //     $user = User::create([
    //         "name" => $this->faker->name,
    //         "email" => $this->faker->email,
    //         "email_verified_at" => now(),
    //         "password" => Hash::make("password")
    //     ]);
    //     $phone = UserPhone::create([
    //         "user_id" => $user->id,
    //         "number" => rand(123456789, 999999999),
    //         "country_code" => rand(1, 999)
    //     ]);
    //     $wallet = Wallet::create([
    //         "user_id" => $user->id,
    //         "balance" => env("SUPPLIER_QUOTATION_PRICE")
    //     ]);
    //     $supplier = Supplier::create([
    //         "user_id" => $user->id,
    //         "commercial_record_number" => "23443234324"
    //     ]);

    //     $a1 = Activity::create([
    //         "name" => $this->faker->name
    //     ]);
    //     $a2 = Activity::create([
    //         "name" => $this->faker->name
    //     ]);

    //     $this->actingAs($user);

    //     $quotation = Quotation::create([
    //         "user_id" => $user->id,
    //         "title" => $this->faker->title,
    //         "description" => $this->faker->text,
    //     ]);
    //     $quotationProduct = QuotationProduct::create([
    //         "quotation_id" => $quotation->id,
    //         "name" => "text",
    //         "quantity" => 3300,
    //     ]);
    //     $response = $this->post("/api/v1/user/quotations/$quotation->id/products/quotations", [
    //         "products" => [
    //             [
    //                 "title" => "test",
    //                 "unit_price" => 3344,
    //                 "description" => "test",
    //                 "quotation_product_id" => $quotationProduct->id
    //             ]
    //         ]

    //     ]);

    //     $response->assertStatus(201);
    // }

    // public function test_user_can_update_his_quotations()
    // {


    //     $user = User::create([
    //         "name" => $this->faker->name,
    //         "email" => $this->faker->email,
    //         "email_verified_at" => now(),
    //         "password" => Hash::make("password")
    //     ]);
    //     $phone = UserPhone::create([
    //         "user_id" => $user->id,
    //         "number" => rand(123456789, 999999999),
    //         "country_code" => rand(1, 999)
    //     ]);

    //     $supplier = Supplier::create([
    //         "user_id" => $user->id,
    //         "commercial_record_number" => "23443234324"
    //     ]);

    //     $quotation = Quotation::create([
    //         "user_id" => $user->id,
    //         "title" => $this->faker->title,
    //         "description" => $this->faker->text,
    //     ]);

    //     $quotation = QuotationQuotation::create([
    //         "user_id" => $user->id,
    //         "title" => $this->faker->title,
    //         "description" => $this->faker->text,
    //         "quotation_id" => $quotation->id,
    //         "amount" => 344234
    //     ]);

    //     $a1 = Activity::create([
    //         "name" => "a1"
    //     ]);
    //     $a2 = Activity::create([
    //         "name" => "a2"
    //     ]);

    //     $this->actingAs($user);

    //     $response = $this->put("/api/v1/user/quotations/$quotation->id/quotations/$quotation->id", [
    //         "title" => $this->faker->title,
    //         "description" => $this->faker->text,
    //         "amount" => 3432434
    //     ]);


    //     $response->assertStatus(200);
    // }

    // public function test_user_can_get_all_quotation_quotations()
    // {


    //     $user = User::create([
    //         "name" => "test",
    //         "email" => "testtesttest@example.com",
    //         "phone" => "96624241242",
    //         "email_verified_at" => now(),
    //         "password" => Hash::make("password")
    //     ]);


    //     $supplier = Supplier::create([
    //         "user_id" => $user->id,
    //         "commercial_record_number" => "23443234324"
    //     ]);

    //     $quotation = Quotation::create([
    //         "user_id" => $user->id,
    //         "title" => $this->faker->title,
    //         "description" => $this->faker->text,
    //     ]);

    //     $quotation = QuotationQuotation::create([
    //         "user_id" => $user->id,
    //         "title" => $this->faker->title,
    //         "description" => $this->faker->text,
    //         "quotation_id" => $quotation->id,
    //         "amount" => 344234
    //     ]);

    //     $a1 = Activity::create([
    //         "name" => "a1"
    //     ]);
    //     $a2 = Activity::create([
    //         "name" => "a2"
    //     ]);

    //     $this->actingAs($user);

    //     $response = $this->get("/api/v1/user/quotations/$quotation->id/quotations");

    //     $response->assertStatus(200);
    // }

    // public function test_user_can_get_all_his_all_quotations()
    // {


    //     $user = User::create([
    //         "name" => "test",
    //         "email" => "testtesttest@example.com",
    //         "phone" => "96624241242",
    //         "email_verified_at" => now(),
    //         "password" => Hash::make("password")
    //     ]);


    //     $supplier = Supplier::create([
    //         "user_id" => $user->id,
    //         "commercial_record_number" => "23443234324"
    //     ]);

    //     $quotation = Quotation::create([
    //         "user_id" => $user->id,
    //         "title" => $this->faker->title,
    //         "description" => $this->faker->text,
    //     ]);

    //     $quotation = QuotationQuotation::create([
    //         "user_id" => $user->id,
    //         "title" => $this->faker->title,
    //         "description" => $this->faker->text,
    //         "quotation_id" => $quotation->id,
    //         "amount" => 344234
    //     ]);

    //     $a1 = Activity::create([
    //         "name" => "a1"
    //     ]);
    //     $a2 = Activity::create([
    //         "name" => "a2"
    //     ]);

    //     $this->actingAs($user);

    //     $response = $this->get("/api/v1/user/quotations/all");

    //     $response->assertStatus(200);
    // }


    // public function test_buyer_can_get_his_own_quotations()
    // {


    //     $user = User::create([
    //         "name" => "test",
    //         "email" => "testtesttest@example.com",
    //         "phone" => "96624241242",
    //         "email_verified_at" => now(),
    //         "password" => Hash::make("password")
    //     ]);


    //     $supplier = Supplier::create([
    //         "user_id" => $user->id,
    //         "commercial_record_number" => "23443234324"
    //     ]);

    //     $quotation = Quotation::create([
    //         "user_id" => $user->id,
    //         "title" => $this->faker->title,
    //         "description" => $this->faker->text,
    //     ]);

    //     $quotation = QuotationQuotation::create([
    //         "user_id" => $user->id,
    //         "title" => $this->faker->title,
    //         "description" => $this->faker->text,
    //         "quotation_id" => $quotation->id,
    //         "amount" => 344234
    //     ]);

    //     $a1 = Activity::create([
    //         "name" => "a1"
    //     ]);
    //     $a2 = Activity::create([
    //         "name" => "a2"
    //     ]);

    //     $this->actingAs($user);

    //     $response = $this->get("/api/v1/user/quotations/$quotation->id/quotations?type=own");

    //     $response->assertStatus(200);
    // }

    // public function test_user_can_get_show_any_quotation()
    // {


    //     $user = User::create([
    //         "name" => "test",
    //         "email" => "testtesttest@example.com",
    //         "phone" => "96624241242",
    //         "email_verified_at" => now(),
    //         "password" => Hash::make("password")
    //     ]);

    //     $supplier = Supplier::create([
    //         "user_id" => $user->id,
    //         "commercial_record_number" => "23443234324"
    //     ]);


    //     $this->actingAs($user);

    //     $quotation = Quotation::create([
    //         "user_id" => $user->id,
    //         "title" => $this->faker->title,
    //         "description" => $this->faker->text,
    //     ]);

    //     $quotation = QuotationQuotation::create([
    //         "user_id" => $user->id,
    //         "title" => $this->faker->title,
    //         "description" => $this->faker->text,
    //         "quotation_id" => $quotation->id,
    //         "amount" => 344234
    //     ]);

    //     $a1 = Activity::create([
    //         "name" => "a1"
    //     ]);

    //     $user->activities()->attach($a1->id);

    //     $response = $this->get("/api/v1/user/quotations/$quotation->id/quotations/$quotation->id");

    //     $response->assertStatus(200);
    // }

    // public function test_user_can_get_delete_quotations()
    // {


    //     $user = User::create([
    //         "name" => "test",
    //         "email" => "testtesttest@example.com",
    //         "phone" => "96624241242",
    //         "email_verified_at" => now(),
    //         "password" => Hash::make("password")
    //     ]);


    //     $this->actingAs($user);

    //     $supplier = Supplier::create([
    //         "user_id" => $user->id,
    //         "commercial_record_number" => "23443234324"
    //     ]);


    //     $quotation = Quotation::create([
    //         "user_id" => $user->id,
    //         "title" => $this->faker->title,
    //         "description" => $this->faker->text,
    //     ]);

    //     $quotation = QuotationQuotation::create([
    //         "user_id" => $user->id,
    //         "title" => $this->faker->title,
    //         "description" => $this->faker->text,
    //         "quotation_id" => $quotation->id,
    //         "amount" => 344234
    //     ]);

    //     $a1 = Activity::create([
    //         "name" => "a1"
    //     ]);

    //     $user->activities()->attach($a1->id);

    //     $response = $this->delete("/api/v1/user/quotations/$quotation->id/quotations/$quotation->id");

    //     $response->assertStatus(204);
    // }

    // public function test_buyer_can_accept_any_quotation()
    // {


    //     $user = User::create([
    //         "name" => $this->faker->name,
    //         "email" => $this->faker->email,
    //         "email_verified_at" => now(),
    //         "password" => Hash::make("password")
    //     ]);
    //     $phone = UserPhone::create([
    //         "user_id" => $user->id,
    //         "number" => rand(123456789, 999999999),
    //         "country_code" => rand(1, 999)
    //     ]);
    //     $supplier = Supplier::create([
    //         "user_id" => $user->id,
    //         "commercial_record_number" => "23443234324"
    //     ]);

    //     $quotation = Quotation::create([
    //         "user_id" => $user->id,
    //         "title" => $this->faker->title,
    //         "description" => $this->faker->text,
    //     ]);

    //     $quotation = QuotationQuotation::create([
    //         "user_id" => $user->id,
    //         "title" => $this->faker->title,
    //         "description" => $this->faker->text,
    //         "quotation_id" => $quotation->id,
    //         "amount" => 344234
    //     ]);

    //     $a1 = Activity::create([
    //         "name" => "a1"
    //     ]);
    //     $a2 = Activity::create([
    //         "name" => "a2"
    //     ]);

    //     $this->actingAs($user);

    //     $response = $this->put("/api/v1/user/quotations/$quotation->id/quotations/$quotation->id/accept", []);


    //     $response->assertStatus(200);
    // }
}
