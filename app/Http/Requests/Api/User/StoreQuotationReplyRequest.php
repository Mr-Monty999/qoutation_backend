<?php

namespace App\Http\Requests\Api\User;

use Illuminate\Foundation\Http\FormRequest;

class StoreQuotationReplyRequest extends FormRequest
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
            "products.*.unit_price" => "nullable|numeric|min:1",
            "products.*.title" => "nullable|string",
            "products.*.description" => "nullable|string",
            "products.*.quotation_product_id" => "required|numeric|integer|exists:quotation_products,id",


        ];
    }
    public function attributes()
    {
        return [
            "products.*.unit_price" => trans("messages.unit price"),
            "products.*.title" => trans("messages.title"),
            "products.*.description" => trans("messages.description")
        ];
    }
}
