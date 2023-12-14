<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

/**
 * Class TelrService.
 */
class TelrService
{

    public static function checkout($amount, $uniqueNumber, $locale = "ar", $description = "")
    {
        $response =  Http::asForm()->post(env("TELR_URL"), [
            "ivp_method" => "create",
            "ivp_authkey" => env("TELR_AUTH_KEY"),
            "ivp_store" => env("TELR_STORE_ID"),
            "ivp_amount" => $amount,
            "ivp_currency" => env("TELR_CURRENCY"),
            "ivp_test" => env("TELR_TEST_MODE"),
            "ivp_cart" => $uniqueNumber,
            "ivp_desc" => $description,
            "ivp_lang" => $locale,
            "return_auth" => route("user.recharge.success", $uniqueNumber),
            "return_decl" => route("user.recharge.declined", $uniqueNumber),
            "return_can" => route("user.recharge.cancelled", $uniqueNumber)
        ]);

        return response($response);
    }
}
