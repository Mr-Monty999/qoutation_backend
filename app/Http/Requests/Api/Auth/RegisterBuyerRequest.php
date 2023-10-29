<?php

namespace App\Http\Requests\Api\Auth;

use Illuminate\Foundation\Http\FormRequest;

class RegisterBuyerRequest extends FormRequest
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
            "name" => "required|string",
            "email" => "required|email|unique:users,email",
            "country_code" => "required|string|in:966",
            "phone" => "required|numeric|unique:users,phone",
            "password" => "required|string|min:8",
            "password_confirmation" => "required|string|same:password",
            "birthdate" => "nullable|date",
            "image" => "nullable|image"
        ];
    }
}
