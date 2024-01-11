<?php

namespace Tests\Feature\User;

use App\Models\Message;
use App\Models\MessageRecipient;
use App\Models\User;
use App\Models\Wallet;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class MessageTest extends TestCase
{
    use WithFaker;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_user_can_send_message()
    {
        app()->setLocale("en");
        $user = User::create([
            "name" => $this->faker->name,
            "email" => $this->faker->email,
            "phone" => $this->faker->phoneNumber,
            "email_verified_at" => now(),
            "password" => Hash::make("password")
        ]);

        $receiverUser = User::create([
            "name" => $this->faker->name,
            "email" => $this->faker->email,
            "phone" => $this->faker->phoneNumber,
            "email_verified_at" => now(),
            "password" => Hash::make("password")
        ]);


        $wallet = Wallet::create([
            "user_id" => $user->id,
            "balance" => env("SUPPLIER_QUOTATION_PRICE")
        ]);

        $message = Message::create([
            "sender_id" => $user->id,
            "title" => $this->faker->title,
            "body" => $this->faker->text,

        ]);



        $this->actingAs($user);

        $response = $this->post(
            "/api/v1/user/messages",
            [
                "receiver_email" => $receiverUser->email,
                "title" => $this->faker->title,
                "body" => $this->faker->text,
                "message_id" => $message->id

            ]
        );

        $response->assertStatus(201);
    }
    public function test_user_can_show_message()
    {
        $senderUser = User::create([
            "name" => $this->faker->name,
            "email" => $this->faker->email,
            "phone" => $this->faker->phoneNumber,
            "email_verified_at" => now(),
            "password" => Hash::make("password")
        ]);

        $receiverUser = User::create([
            "name" => $this->faker->name,
            "email" => $this->faker->email,
            "phone" => $this->faker->phoneNumber,
            "email_verified_at" => now(),
            "password" => Hash::make("password")
        ]);


        $wallet = Wallet::create([
            "user_id" => $senderUser->id,
            "balance" => env("SUPPLIER_QUOTATION_PRICE")
        ]);


        $this->actingAs($senderUser);

        $message = Message::create([
            "sender_id" => $senderUser->id,
            "title" => $this->faker->name,
            "body" => $this->faker->text
        ]);

        $messageRecipients = MessageRecipient::create([
            "receiver_id" => $receiverUser->id,
            "message_id" => $message->id
        ]);
        $response = $this->get("/api/v1/user/messages/$message->id");

        $response->assertStatus(200);
    }
    public function test_user_can_show_message_recipient()
    {
        $senderUser = User::create([
            "name" => $this->faker->name,
            "email" => $this->faker->email,
            "phone" => $this->faker->phoneNumber,
            "email_verified_at" => now(),
            "password" => Hash::make("password")
        ]);

        $receiverUser = User::create([
            "name" => $this->faker->name,
            "email" => $this->faker->email,
            "phone" => $this->faker->phoneNumber,
            "email_verified_at" => now(),
            "password" => Hash::make("password")
        ]);


        $wallet = Wallet::create([
            "user_id" => $senderUser->id,
            "balance" => env("SUPPLIER_QUOTATION_PRICE")
        ]);


        $this->actingAs($senderUser);

        $message = Message::create([
            "sender_id" => $senderUser->id,
            "title" => $this->faker->name,
            "body" => $this->faker->text
        ]);

        $messageRecipients = MessageRecipient::create([
            "receiver_id" => $receiverUser->id,
            "message_id" => $message->id
        ]);
        $response = $this->get("/api/v1/user/messages/recipients/$messageRecipients->id");

        $response->assertStatus(200);
    }
    public function test_user_can_read_message_recipient()
    {
        $senderUser = User::create([
            "name" => $this->faker->name,
            "email" => $this->faker->email,
            "phone" => $this->faker->phoneNumber,
            "email_verified_at" => now(),
            "password" => Hash::make("password")
        ]);

        $receiverUser = User::create([
            "name" => $this->faker->name,
            "email" => $this->faker->email,
            "phone" => $this->faker->phoneNumber,
            "email_verified_at" => now(),
            "password" => Hash::make("password")
        ]);


        $wallet = Wallet::create([
            "user_id" => $senderUser->id,
            "balance" => env("SUPPLIER_QUOTATION_PRICE")
        ]);


        $this->actingAs($senderUser);

        $message = Message::create([
            "sender_id" => $senderUser->id,
            "title" => $this->faker->name,
            "body" => $this->faker->text
        ]);

        $messageRecipient = MessageRecipient::create([
            "receiver_id" => $receiverUser->id,
            "message_id" => $message->id
        ]);
        $response = $this->put("/api/v1/user/messages/recipients/$messageRecipient->id/read");

        $response->assertStatus(200);
    }

    public function test_user_can_show_all_received_messages()
    {
        $user = User::create([
            "name" => $this->faker->name,
            "email" => $this->faker->email,
            "phone" => $this->faker->phoneNumber,
            "email_verified_at" => now(),
            "password" => Hash::make("password")
        ]);

        $wallet = Wallet::create([
            "user_id" => $user->id,
            "balance" => env("SUPPLIER_QUOTATION_PRICE")
        ]);



        $this->actingAs($user);

        $response = $this->get("/api/v1/user/messages?type=received");

        $response->assertStatus(200);
    }
    public function test_user_can_show_all_sent_messages()
    {
        $user = User::create([
            "name" => $this->faker->name,
            "email" => $this->faker->email,
            "phone" => $this->faker->phoneNumber,
            "email_verified_at" => now(),
            "password" => Hash::make("password")
        ]);

        $wallet = Wallet::create([
            "user_id" => $user->id,
            "balance" => env("SUPPLIER_QUOTATION_PRICE")
        ]);



        $this->actingAs($user);
        $response = $this->get("/api/v1/user/messages?type=sent");

        $response->assertStatus(200);
    }
}