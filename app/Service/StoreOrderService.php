<?php

namespace App\Service;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Stock;
use Illuminate\Support\Facades\DB;

class StoreOrderService {

    public function store(array $data) {
        DB::beginTransaction();



        try {
            $order = Order::create([
                'customer' => $data['customer'],
                'created_at' => now(),
                'warehouse_id' => Stock::where('product_id', $data['items'][0]['product_id'])->first()->warehouse_id,
                'status' => Order::ACTIVE,
            ]);

            
            foreach ($data['items'] as $value) {
                $product = Product::findOrFail($value['product_id']);
                

                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id'=> $product->id,
                    'count'=> $value['count'],
                    'price'=> $product->price,
            ]);

            
            DB::table('stocks')->where('product_id', $value['product_id'])
            ->update([
                'stock' => DB::raw("stock - {$value['count']}")
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
                'error' => 'Ошибка',
                'details' => $exception,
            ], 500);
        }
    }
}