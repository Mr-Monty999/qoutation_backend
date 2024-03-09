<?php

namespace App\Http\Requests\Api\Auth;

use Illuminate\Foundation\Http\FormRequest;

class SendRegisterOtp extends FormRequest
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

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            "email_or_phone" => "required|string",
            // "send_via" => "required|string|in:email,phone",
            "type" => "required|string|in:reset_password,phone_confirmation,email_confirmation,update_email"

        ];
    }
}
