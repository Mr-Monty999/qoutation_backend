<?php

namespace Tests\Feature\User;

use App\Models\Activity;
use App\Models\Quotation;
use App\Models\QuotationProduct;
use App\Models\Supplier;
use App\Models\User;
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
}
