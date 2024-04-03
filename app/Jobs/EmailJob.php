<?php

namespace App\Jobs;

use App\Mail\AcceptQuotationMail;
use App\Mail\AcceptServiceReplyNotificationMail;
use App\Mail\AcceptSupplierNotificationMail;
use App\Mail\EmailConfirmationMail;
use App\Mail\ResetPasswordMail;
use App\Mail\SendNewMessageNotification;
use App\Mail\SendQuotationNotificationMail;
use App\Mail\SendServiceReplyNotificationMail;
use App\Mail\ServiceCompleteNotificationMail;
use App\Mail\UpdateQuotationReplyNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class EmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    private $data;
    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        if ($this->data["type"] == "service_complete") {
            Mail::to($this->data["target_email"])->send(new ServiceCompleteNotificationMail([
                "buyer_name" => $this->data["buyer_name"],
                "buyer_phone" => $this->data["buyer_phone"],
                "buyer_email" => $this->data["buyer_email"],
                "service_reply_title" => $this->data["service_reply_title"],
                "service_reply_price" => $this->data["service_reply_price"],
                "service_reply_description" => $this->data["service_reply_description"],
                "service_id" => $this->data["service_id"],
                "service_reply_id" => $this->data["service_reply_id"],
            ]));
        } else if ($this->data["type"] == "accept_service_reply") {
            Mail::to($this->data["target_email"])->send(new AcceptServiceReplyNotificationMail([
                "buyer_name" => $this->data["buyer_name"],
                "buyer_phone" => $this->data["buyer_phone"],
                "buyer_email" => $this->data["buyer_email"],
                "service_reply_title" => $this->data["service_reply_title"],
                "service_reply_price" => $this->data["service_reply_price"],
                "service_reply_description" => $this->data["service_reply_description"],
                "service_id" => $this->data["service_id"],
                "service_reply_id" => $this->data["service_reply_id"],
            ]));
        } else if ($this->data["type"] == "send_service_reply") {
            Mail::to($this->data["target_email"])->send(new SendServiceReplyNotificationMail([
                "supplier_name" => $this->data["supplier_name"],
                // "supplier_phone" => $this->data["supplier_phone"],
                // "supplier_email" =>  $this->data["supplier_email"],
                "service_reply_title" => $this->data["service_reply_title"],
                "service_reply_price" => $this->data["service_reply_price"],
                "service_reply_description" => $this->data["service_reply_description"],
                "service_id" => $this->data["service_id"],
                "service_reply_id" => $this->data["service_reply_id"],

            ]));
        } else if ($this->data["type"] == "send_quotation_reply") {
            Mail::to($this->data["target_email"])->send(new SendQuotationNotificationMail([
                "supplier_name" => $this->data["supplier_name"],
                // "supplier_phone" => $this->data["supplier_phone"],
                // "supplier_email" =>  $this->data["supplier_email"],
                // "quotation_reply_title" => $quotation->title,
                // "quotation_reply_price" => $quotation->amount,
                // "quotation_reply_description" => $quotation->description,
                "quotation_id" => $this->data["quotation_id"],
                "invoice_id" => $this->data["invoice_id"]

            ]));
        } else if ($this->data["type"] == "update_quotation_reply") {

            Mail::to($this->data["target_email"])->send(new UpdateQuotationReplyNotification([
                "supplier_name" => $this->data["supplier_name"],
                // "supplier_phone" => $user->phone->country_code . $user->phone->number,
                // "supplier_email" => $user->email,
                // "quotation_reply_title" => $quotation->title,
                // "quotation_reply_price" => $quotation->amount,
                // "quotation_reply_description" => $quotation->description,
                "quotation_id" => $this->data["quotation_id"],
                "invoice_id" => $this->data["invoice_id"]

            ]));
        } else if ($this->data["type"] == "accept_quotation_reply") {
            Mail::to($this->data["target_email"])->send(new AcceptQuotationMail([
                "buyer_name" => $this->data["buyer_name"],
                "buyer_phone" => $this->data["buyer_phone"],
                "buyer_email" => $this->data["buyer_email"],
                // "quotation_reply_title" => $quotation->title,
                // "quotation_reply_price" => $quotation->amount,
                // "quotation_reply_description" => $quotation->description,
                "quotation_id" => $this->data["quotation_id"],
                "invoice_id" => $this->data["invoice_id"]

            ]));
        } else if ($this->data["type"] == "email_confirmation_otp") {
            Mail::to($this->data["identifier"])->send(new EmailConfirmationMail($this->data["otp"]));
        } else if ($this->data["type"] == "reset_password_otp") {
            Mail::to($this->data["identifier"])->send(new ResetPasswordMail($this->data["otp"]));
        } else if ($this->data["type"] == "send_message") {
            Mail::to($this->data["target_email"])->send(new SendNewMessageNotification([
                "message_recipient_id" => $this->data["message_recipient_id"],
                "sender_name" => $this->data["sender_name"]

            ]));
        } else if ($this->data["type"] == "send_supplier_accept_notification") {
            Mail::to($this->data["target_email"])->send(new AcceptSupplierNotificationMail([]));
        }
    }
}
