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
    private $keys;
    public function __construct()
    {

        $this->keys = implode(",", config("settings.website_settings_keys"));
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
            "footer_copyright_text" => "nullable|string",

        ];
    }
}
