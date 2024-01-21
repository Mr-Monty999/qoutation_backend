<?php

namespace App\Http\Requests\Api\User;

use App\Models\Country;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;

class UpdateSupplierProfileRequest extends FormRequest
{

    public function prepareForValidation()
    {
        if ($this->phone && $this->country_id) {
            $country = Country::findOrFail($this->country_id);
            $phone = ltrim($this->phone, '0');

            if (strlen($phone) != 9)
                throw ValidationException::withMessages(['phone' => trans("messages.phone number must equal 9 digits")]);


            // $fullPhone = $country->code .  $phone;

            // $this->merge([
            //     "phone" => $fullPhone
            // ]);
        }
    }

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
            "phone" => "required|numeric|unique:user_phones,number," . auth()->user()->phone->id,
            "birthdate" => "nullable|date",
            "activity_description" => "nullable|string",
            "commercial_record_number" => "required|numeric|unique:suppliers,commercial_record_number," . auth()->user()->supplier->id,
            "commercial_record_image" => "nullable|image|max:2048",
            "activity_ids" => "required|array",
            "image" => "nullable|image|max:2048",
            "country_id" => "required|numeric|exists:countries,id",
            "city_id" => "required|numeric|exists:cities,id",
            "neighbourhood_id" => "required|numeric|exists:neighbourhoods,id",
            "lat" => "nullable|string",
            "lng" => "nullable|string",
            "address" => "nullable|string"
        ];
    }
}
