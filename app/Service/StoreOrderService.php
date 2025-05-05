<?php

namespace App\Service;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Stock;
use Illuminate\Support\Facades\DB;

class StoreOrderService  {

    public function store(array $data) {
        DB::beginTransaction();
        try {
            $order = Order::create([
                'customer' => $data['customer'],
                'created_at' => now(),
                'warehouse_id' => $data['warehouse_id'],
                'status' => Order::ACTIVE,
            ]);



            $productIds = collect($data['items'])->pluck('product_id');
            $products = Product::whereIn('id', $productIds)->get()->keyBy('id');


            foreach ($data['items'] as $value) {
                $stock = Stock::where('product_id', $value['product_id'])
                ->where('warehouse_id', $data['warehouse_id'])
                ->lockForUpdate()
                ->first();
                
                if ($stock->stock < $value['count']) {
                    throw new \Exception("Недостаточно товара {$products[$value['product_id']]->name} на складе!");
                }

                
                DB::table('stocks')
                ->where('product_id', $value['product_id'])
                ->where('warehouse_id', $data['warehouse_id'])
                ->decrement('stock', $value['count']);

                
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $value['product_id'],
                    'count' => $value['count'],
                    'price' => $products[$value['product_id']]->price,
                ]); 
        }
        

        DB::commit();


            return response()->json([
                'message' => 'Заказ создан',
                'order' => $order,
            ], 201);
        } catch(\Exception $exception) {
            DB::rollBack();
            
            

            return response()->json([
                'error' => 'Не удалось создать заказ!',
                'message' => $exception->getMessage(),
            ], 500);
        }
    }
}