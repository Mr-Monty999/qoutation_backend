<?php

namespace App\Http\Requests\Api\Auth;

use App\Models\Country;
use App\Models\User;
use Dotenv\Exception\ValidationException as ExceptionValidationException;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;

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

    public function prepareForValidation()
    {
        if ($this->phone && $this->country_id) {
            $country = Country::findOrFail($this->country_id);
            $phone = ltrim($this->phone, '0');

            if (strlen($phone) != 9)
                throw ValidationException::withMessages(['phone' => trans("messages.phone number must equal 9 digits")]);


            $fullPhone = $country->code .  $phone;

            $this->merge([
                "phone" => $fullPhone
            ]);
        }
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
            "phone" => "required|numeric|unique:users,phone",
            "password" => "required|string|min:8",
            "password_confirmation" => "required|string|same:password",
            "birthdate" => "nullable|date",
            "image" => "nullable|image",
            "country_id" => "required|numeric",
            "activity_ids" => "required|array",
            "commercial_record_number" => "required|string|unique:suppliers,commercial_record_number",
            "commercial_record_image" => "nullable|image",
            "lat" => "nullable|string",
            "lng" => "nullable|string",
            "activity_description" => "nullable|string",


        ];
    }
}
