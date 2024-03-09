<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

/**
 * Class TelrService.
 */
class TelrService
{

    public static function checkout($amount, $uniqueNumber, $uuid, $description = "", $locale = "ar")
    {

        $response =  Http::withoutVerifying()->asForm()->post(env("TELR_URL"), [
            "ivp_method" => "create",
            "ivp_authkey" => env("TELR_AUTH_KEY"),
            "ivp_store" => env("TELR_STORE_ID"),
            "ivp_amount" => $amount,
            "ivp_currency" => env("TELR_CURRENCY"),
            "ivp_test" => env("TELR_TEST_MODE"),
            "ivp_cart" => $uniqueNumber,
            "ivp_desc" => $description,
            "ivp_lang" => $locale,
            "return_auth" => env('TELR_SUCCESS_CALLBACK') . "/" . $uuid,
            "return_decl" => env("TELR_DECLINED_CALLBACK") . "/" . $uuid,
            "return_can" => env("TELR_CANCELLED_CALLBACK") . "/" . $uuid
        ]);

        return json_decode($response->body());
    }
}
