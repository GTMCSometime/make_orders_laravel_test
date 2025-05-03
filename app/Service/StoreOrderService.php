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
                'warehouse_id' => $data['warehouse_id'],
                'status' => Order::ACTIVE,
            ]);



            foreach ($data['items'] as $value) {
                $stock = Stock::where('product_id', $value['product_id'])
                ->where('warehouse_id', $data['warehouse_id'])
                ->lockForUpdate()
                ->first();
                

                if(!$stock || $stock->stock < $value['count']) {
                    throw new \Exception('Недостаточно товара на складе!');
                }

                
                $stockDiff = $stock->stock -= $value['count'];
                DB::table('stocks')->where('product_id', $value['product_id'])
                ->where('warehouse_id', $data['warehouse_id'])->update(['stock' => $stockDiff]);

                
                $product = Product::findOrFail($value['product_id']);
                

                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id'=> $product->id,
                    'count'=> $value['count'],
                    'price'=> $product->price,
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