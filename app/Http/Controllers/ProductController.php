<?php

namespace App\Http\Controllers;

use App\Http\Resources\ProductWithStockResource;
use App\Models\Product;

class ProductController extends Controller
{
    public function index()
    {
        // просмотр продуктов, с их остатками по складам
        $product = Product::with('stocks.warehouse')->get();
        return response()->json(
            ProductWithStockResource::collection($product)
        );
    }
}
