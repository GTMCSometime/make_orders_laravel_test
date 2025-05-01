<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OrderFilterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            "customer"=> "nullable|string|max:255",
            "created_at"=> "nullable|string",
            "completed_at"=> "nullable|string",
            "warehouse_id"=> "nullable|integer|exists:warehouses,id",
            "status"=> "nullable|string",
        ];
    }
}
