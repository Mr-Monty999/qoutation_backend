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

        $this->keys = implode(",", [
            "website_name",
            "website_logo",
            "home_page_text_1",
            "home_page_text_2",
            "home_page_text_3",
            "home_page_text_4",
            "home_page_text_5",
            "home_page_text_6",
            "home_page_button_1_text",
            "home_page_button_1_url",
            "home_page_feature_1_text",
            "home_page_feature_2_text",
            "home_page_feature_3_text",
            "home_page_feature_3_text",
            "home_page_feature_1_icon",
            "home_page_feature_2_icon",
            "home_page_feature_3_icon",
            "home_page_feature_3_icon",
            "footer_copyright_text",

        ]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            "settings" => "required|array",
            "settings.*.key" => "required|string|in:$this->keys",
            "settings.*.value" => "nullable|string",
            "settings.*.description" => "nullable|string",

        ];
    }
}
