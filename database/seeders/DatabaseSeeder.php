<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Stock;
use App\Models\Warehouse;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        Warehouse::factory()->count(10)->create();
        Product::factory()->count(20)->create()->each(function ($product) {
            Warehouse::all()->each(function ($warehouse) use ($product) {
                Stock::create([
                    "product_id"=> $product->id,
                    "warehouse_id"=> $warehouse->id,
                    'stock' => rand(1,100),
                ]);
        });
    });
    }
}
