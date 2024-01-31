<?php

namespace App\Http\Requests\Api\User;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductQuotationRequest extends FormRequest
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
            "products" => "required|array",
            "products.*.unit_price" => "required|numeric|integer",
            "products.*.title" => "required|string",
            "products.*.description" => "nullable|string",
            "products.*.service_product_id" => "required|numeric|integer|exists:service_products,id",


        ];
    }
    public function attributes()
    {
        return [
            // "amount" => trans("messages.price"),
        ];
    }
}
