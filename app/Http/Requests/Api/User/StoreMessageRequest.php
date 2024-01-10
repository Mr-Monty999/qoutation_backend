<?php

namespace App\Http\Requests\Api\User;

use Illuminate\Foundation\Http\FormRequest;

class StoreMessageRequest extends FormRequest
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
            "receiver_email" => "required|email",
            "title" => "required|string",
            "body" => "required|string",
            "message_id" => "nullable|exists:messages,id",
            "attachments.*" => "nullable|file|max:2048"

        ];
    }
}
