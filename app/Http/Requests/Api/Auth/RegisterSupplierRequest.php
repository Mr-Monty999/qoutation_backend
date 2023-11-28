<?php

namespace App\Http\Requests\Api\Auth;

use Illuminate\Foundation\Http\FormRequest;

class RegisterSupplierRequest extends FormRequest
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
            "phone" => "required|numeric|digits:9",
            "password" => "required|string|min:8",
            "password_confirmation" => "required|string|same:password",
            "birthdate" => "nullable|date",
            "image" => "nullable|image",
            "country_id" => "required|numeric",
            "activity_ids" => "required|array"


        ];
    }
}
