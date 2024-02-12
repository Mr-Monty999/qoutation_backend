<?php

namespace Tests\Feature\User;

use App\Models\Activity;
use App\Models\Buyer;
use App\Models\Quotation;
use App\Models\QuotationInvoice;
use App\Models\QuotationProduct;
use App\Models\QuotationReply;
use App\Models\Supplier;
use App\Models\User;
use App\Models\UserPhone;
use App\Models\Wallet;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class QuotationReplyTest extends TestCase
{
    use WithFaker;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_user_can_send_quotation_reply()
    {
        $user = User::create([
            "name" => "test",
            "email" => "testtesttest@example.com",
            "phone" => "96624241242",
            "email_verified_at" => now(),
            "password" => Hash::make("password")
        ]);

        $phone = UserPhone::create([
            "user_id" => $user->id,
            "number" => $this->faker->numberBetween(123456789, 999999999),
            "country_code" => $this->faker->numberBetween(1, 300)

        ]);


        $this->actingAs($user);

        $wallet = Wallet::create([
            "user_id" => $user->id,
            "balance" => env("SUPPLIER_QUOTATION_PRICE")
        ]);

        $supplier = Supplier::create([
            "user_id" => $user->id,
            "commercial_record_number" => rand(1234567, 99999999)
        ]);

        $quotation = Quotation::create([
            "user_id" => $user->id,
            "title" => $this->faker->title,
            "description" => $this->faker->text,
        ]);

        $a1 = Activity::create([
            "name" => "a1"
        ]);

        $quotation->activities()->attach($a1->id);

        $quotationProducts[] = QuotationProduct::create([
            "quotation_id" => $quotation->id,
            "quantity" => $this->faker->numberBetween(1, 300),
            "name" => $this->faker->name

        ]);

        $quotationProducts[] = QuotationProduct::create([
            "quotation_id" => $quotation->id,
            "quantity" => $this->faker->numberBetween(1, 300),
            "name" => $this->faker->name

        ]);

        $quotationProducts[] = QuotationProduct::create([
            "quotation_id" => $quotation->id,
            "quantity" => $this->faker->numberBetween(1, 300),
            "name" => $this->faker->name

        ]);

        $response = $this->post("/api/v1/user/quotations/$quotation->id/replies", [
            "products" => [
                [
                    "quotation_product_id" => $quotationProducts[0]->id,
                    "unit_price" => $this->faker->numberBetween(200, 3000)
                ],
                [
                    "quotation_product_id" => $quotationProducts[1]->id,
                    "unit_price" => $this->faker->numberBetween(200, 3000)
                ],
                [
                    "quotation_product_id" => $quotationProducts[2]->id,
                    "unit_price" => $this->faker->numberBetween(200, 3000)
                ]

            ]
        ]);

        $response->assertStatus(201);
    }

    public function test_user_can_edit_quotation_reply()
    {
        $user = User::create([
            "name" => "test",
            "email" => "testtesttest@example.com",
            "phone" => "96624241242",
            "email_verified_at" => now(),
            "password" => Hash::make("password")
        ]);

        $phone = UserPhone::create([
            "user_id" => $user->id,
            "number" => $this->faker->numberBetween(123456789, 999999999),
            "country_code" => $this->faker->numberBetween(1, 300)

        ]);


        $this->actingAs($user);

        $wallet = Wallet::create([
            "user_id" => $user->id,
            "balance" => env("SUPPLIER_QUOTATION_PRICE")
        ]);

        $supplier = Supplier::create([
            "user_id" => $user->id,
            "commercial_record_number" => rand(1234567, 99999999)
        ]);

        $quotation = Quotation::create([
            "user_id" => $user->id,
            "title" => $this->faker->title,
            "description" => $this->faker->text,
        ]);

        $a1 = Activity::create([
            "name" => "a1"
        ]);

        $quotation->activities()->attach($a1->id);

        $quotationProducts[] = QuotationProduct::create([
            "quotation_id" => $quotation->id,
            "quantity" => $this->faker->numberBetween(1, 300),
            "name" => $this->faker->name

        ]);

        $quotationProducts[] = QuotationProduct::create([
            "quotation_id" => $quotation->id,
            "quantity" => $this->faker->numberBetween(1, 300),
            "name" => $this->faker->name

        ]);

        $quotationProducts[] = QuotationProduct::create([
            "quotation_id" => $quotation->id,
            "quantity" => $this->faker->numberBetween(1, 300),
            "name" => $this->faker->name

        ]);

        $response = $this->put("/api/v1/user/quotations/$quotation->id/replies", [
            "products" => [
                [
                    "quotation_product_id" => $quotationProducts[0]->id,
                    "unit_price" => $this->faker->numberBetween(200, 3000)
                ],
                [
                    "quotation_product_id" => $quotationProducts[1]->id,
                    "unit_price" => $this->faker->numberBetween(200, 3000)
                ],
                [
                    "quotation_product_id" => $quotationProducts[2]->id,
                    "unit_price" => $this->faker->numberBetween(200, 3000)
                ]

            ]
        ]);

        $response->assertStatus(200);
    }

    public function test_user_can_accept_quotation_reply()
    {
        $user = User::create([
            "name" => "test",
            "email" => "testtesttest@example.com",
            "phone" => "96624241242",
            "email_verified_at" => now(),
            "password" => Hash::make("password")
        ]);

        $phone = UserPhone::create([
            "user_id" => $user->id,
            "number" => $this->faker->numberBetween(123456789, 999999999),
            "country_code" => $this->faker->numberBetween(1, 300)

        ]);

        $this->actingAs($user);

        $wallet = Wallet::create([
            "user_id" => $user->id,
            "balance" => env("SUPPLIER_QUOTATION_PRICE")
        ]);

        $buyer = Buyer::create([
            "user_id" => $user->id,
            // "commercial_record_number" => rand(1234567, 99999999)
        ]);

        $quotation = Quotation::create([
            "user_id" => $user->id,
            "title" => $this->faker->title,
            "description" => $this->faker->text,
        ]);

        $a1 = Activity::create([
            "name" => "a1"
        ]);

        $quotation->activities()->attach($a1->id);

        $quotationProduct = QuotationProduct::create([
            "quotation_id" => $quotation->id,
            "quantity" => $this->faker->numberBetween(1, 300),
            "name" => $this->faker->name

        ]);

        $invoice = QuotationInvoice::create([
            "user_id" => $user->id,
            "quotation_id" => $quotation->id,
            "total_inc_tax" => $this->faker->numberBetween(1, 300),
            "total_without_tax" => $this->faker->numberBetween(1, 300),
            "tax_amount" => $this->faker->numberBetween(1, 300),
            "tax_percentage" => $this->faker->numberBetween(1, 300)
        ]);


        $quotationReply = QuotationReply::create([
            "quotation_invoice_id" => $invoice->id,
            "quotation_id" => $quotation->id,
            "user_id" => $user->id,
            "quotation_product_id" => $quotationProduct->id,
            "unit_price" => $this->faker->numberBetween(1, 12342)
        ]);

        $response = $this->put("/api/v1/user/quotations/$quotation->id/replies/$quotationReply->id/accept");

        $response->assertStatus(200);
    }

    public function test_user_can_get_quotation_invoice()
    {
        $user = User::create([
            "name" => "test",
            "email" => "testtesttest@example.com",
            "phone" => "96624241242",
            "email_verified_at" => now(),
            "password" => Hash::make("password")
        ]);

        $phone = UserPhone::create([
            "user_id" => $user->id,
            "number" => $this->faker->numberBetween(123456789, 999999999),
            "country_code" => $this->faker->numberBetween(1, 300)

        ]);

        $this->actingAs($user);

        $wallet = Wallet::create([
            "user_id" => $user->id,
            "balance" => env("SUPPLIER_QUOTATION_PRICE")
        ]);

        $buyer = Buyer::create([
            "user_id" => $user->id,
            // "commercial_record_number" => rand(1234567, 99999999)
        ]);

        $quotation = Quotation::create([
            "user_id" => $user->id,
            "title" => $this->faker->title,
            "description" => $this->faker->text,
        ]);

        $a1 = Activity::create([
            "name" => "a1"
        ]);

        $quotation->activities()->attach($a1->id);

        $quotationProduct = QuotationProduct::create([
            "quotation_id" => $quotation->id,
            "quantity" => $this->faker->numberBetween(1, 300),
            "name" => $this->faker->name

        ]);

        $invoice = QuotationInvoice::create([
            "user_id" => $user->id,
            "quotation_id" => $quotation->id,
            "total_inc_tax" => $this->faker->numberBetween(1, 300),
            "total_without_tax" => $this->faker->numberBetween(1, 300),
            "tax_amount" => $this->faker->numberBetween(1, 300),
            "tax_percentage" => $this->faker->numberBetween(1, 300)
        ]);


        $quotationReply = QuotationReply::create([
            "quotation_invoice_id" => $invoice->id,
            "quotation_id" => $quotation->id,
            "user_id" => $user->id,
            "quotation_product_id" => $quotationProduct->id,
            "unit_price" => $this->faker->numberBetween(1, 12342)
        ]);

        $response = $this->get("/api/v1/user/quotations/$quotation->id/replies/invoices/$invoice->id");

        $response->assertStatus(200);
    }
}
