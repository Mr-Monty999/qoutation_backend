<?php

namespace App\Http\Requests\Api\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateSettingRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }
    public function __construct()
    {
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            "website_name" => "nullable|string",
            "website_logo" => "nullable|image",
            "supplier_quotation_price" => "nullable|numeric",
            "supplier_wallet_signup_gift" => "nullable|numeric",
            "home_page_image_1" => "nullable|image",
            "home_page_text_1" => "nullable|string",
            "home_page_text_2" => "nullable|string",
            "home_page_text_3" => "nullable|string",
            "home_page_text_4" => "nullable|string",
            "home_page_text_5" => "nullable|string",
            "home_page_text_6" => "nullable|string",
            "home_page_button_1_text" => "nullable|string",
            "home_page_button_1_url" => "nullable|string",
            "home_page_feature_1_text" => "nullable|string",
            "home_page_feature_2_text" => "nullable|string",
            "home_page_feature_3_text" => "nullable|string",
            "home_page_feature_4_text" => "nullable|string",
            "home_page_feature_1_icon" => "nullable|image",
            "home_page_feature_2_icon" => "nullable|image",
            "home_page_feature_3_icon" => "nullable|image",
            "home_page_feature_4_icon" => "nullable|image",
            "about_page_text_1" => "nullable|string",
            "contact_page_text_1" => "nullable|string",
            "contact_page_text_2" => "nullable|string",
            "contact_page_text_3" => "nullable|string",
            "contact_page_text_4" => "nullable|string",
            "contact_page_text_5" => "nullable|string",
            "contact_page_text_6" => "nullable|string",
            "contact_page_text_7" => "nullable|string",
            "contact_page_text_8" => "nullable|string",
            "contact_page_text_9" => "nullable|string",
            "contact_page_image_1" => "nullable|image",
            "landing_page_footer_text_1" => "nullable|string",

        ];
    }
}
