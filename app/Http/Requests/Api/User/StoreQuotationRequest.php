<?php

namespace App\Http\Requests\Api\User;

use Illuminate\Foundation\Http\FormRequest;

class StoreQuotationRequest extends FormRequest
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
            "title" => "required|string",
            "description" => "required|string",
            "activity_ids" => "required|array",
            "country_id" => "required|numeric|exists:countries,id",
            "city_id" => "required|numeric|exists:cities,id",
            "neighbourhood_id" => "required|numeric|exists:neighbourhoods,id",
            "products" => "required|array",
            "products.*.name" => "required|string",
            "products.*.quantity" => "required|numeric|integer",
            "products.*.description" => "nullable|string"

        ];
    }
    public function attributes()
    {
        return [
            "products.*.name" => trans("messages.name"),
            "products.*.quantity" => trans("messages.quantity"),
            "products.*.description" => trans("messages.description"),
            "country_id" => trans("messages.country")
        ];
    }
}
