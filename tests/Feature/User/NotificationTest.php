<?php

namespace Tests\Feature\User;

use App\Models\User;
use App\Models\UserPhone;
use App\Models\Wallet;
use App\Notifications\SendQuotationNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class NotificationTest extends TestCase
{
    use WithFaker;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_user_can_get_all_latest_notifications()
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
        $wallet = Wallet::create([
            "user_id" => $user->id,
            "balance" => env("SUPPLIER_QUOTATION_PRICE")
        ]);

        $this->actingAs($user);
        $response = $this->get('/api/v1/user/notifications');

        $response->assertStatus(200);
    }
    public function test_user_can_read_any_notification()
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
        $wallet = Wallet::create([
            "user_id" => $user->id,
            "balance" => env("SUPPLIER_QUOTATION_PRICE")
        ]);



        $this->actingAs($user);

        $user->notify(new SendQuotationNotification([
            "quotation_id" =>  $user->id,
            "quotation_id" =>  $user->id,
            "sender_id" => $user->id
        ]));

        $notification = $user->notifications->first();

        $response = $this->put("/api/v1/user/notifications/$notification->id/read");

        $response->assertStatus(200);
    }
    public function test_user_can_get_notifications_count()
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
        $wallet = Wallet::create([
            "user_id" => $user->id,
            "balance" => env("SUPPLIER_QUOTATION_PRICE")
        ]);



        $this->actingAs($user);

        $user->notify(new SendQuotationNotification([
            "quotation_id" =>  $user->id,
            "quotation_id" =>  $user->id,
            "sender_id" => $user->id
        ]));

        $notification = $user->notifications->first();

        $response = $this->get("/api/v1/user/notifications/count");

        $response->assertStatus(200);
    }
    public function test_user_can_read_his_all_notifications()
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
        $wallet = Wallet::create([
            "user_id" => $user->id,
            "balance" => env("SUPPLIER_QUOTATION_PRICE")
        ]);



        $this->actingAs($user);

        $user->notify(new SendQuotationNotification([
            "quotation_id" =>  $user->id,
            "quotation_id" =>  $user->id,
            "sender_id" => $user->id
        ]));

        $notification = $user->notifications->first();

        $response = $this->put("/api/v1/user/notifications/readall");

        $response->assertStatus(200);
    }
}
