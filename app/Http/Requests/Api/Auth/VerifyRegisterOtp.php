<?php

namespace App\Http\Requests\Api\Auth;

use Illuminate\Foundation\Http\FormRequest;

class VerifyRegisterOtp extends FormRequest
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
            // "user_id" => "required|numeric",
            "email_or_phone" => "required|string",
            "otp_code" => "required|string",
            "type" => "required|string|in:forget_password,phone_confirmation,email_confirmation"
        ];
    }
}
