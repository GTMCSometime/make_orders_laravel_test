<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductWithStockResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'=> $this->id,
            'name' => $this->name,
            'price' => $this->price,
            'stock' => $this->stocks->map(function($stock) {
                return [
                    'warehouse_id' => $stock->warehouse_id, // получаем ID склада
                    'warehouse_name'=> $stock->warehouse->name, // получаем название склада
                    'stock' => $stock->stock, // получаем количество товара на складе
                ];
            }),
        ];
    }
}
