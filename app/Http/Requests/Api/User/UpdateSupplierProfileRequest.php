<?php

namespace App\Http\Requests\Api\User;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSupplierProfileRequest extends FormRequest
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
            "phone" => "required|numeric|unique:users,phone," . auth()->user()->id,
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
