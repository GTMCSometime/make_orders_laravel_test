<?php

namespace App\Http\Requests\Order;

use Illuminate\Foundation\Http\FormRequest;

class OrderFilterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    
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
