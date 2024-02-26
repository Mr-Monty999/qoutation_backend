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
            "settings" => "required|array",
            "settings.*.key" => "required|string|in:$this->keys",
            "settings.*.value" => "nullable|string",
            "settings.*.description" => "nullable|string",

        ];
    }
}
