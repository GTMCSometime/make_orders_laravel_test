<?php

namespace App\Service\Order;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Stock;
use App\Models\StockMovement;
use Illuminate\Support\Facades\DB;

class StoreOrderService  {

    public function store(array $data) {
        DB::beginTransaction();
        try {
            // создаем заказ
            $order = Order::create([
                'customer' => $data['customer'],
                'created_at' => now(),
                'warehouse_id' => $data['warehouse_id'],
                'status' => Order::ACTIVE,
            ]);


            // получаем все товары в коллекцию
            $productIds = collect($data['items'])->pluck('product_id');
            $products = Product::whereIn('id', $productIds)->get()->keyBy('id');

            // проверяем, можно ли заказать товар
            foreach ($data['items'] as $value) {
                $stock = Stock::where('product_id', $value['product_id'])
                ->where('warehouse_id', $data['warehouse_id'])
                ->lockForUpdate()
                ->first();
                
                if ($stock->stock < $value['count']) {
                    throw new \Exception("Недостаточно товара {$products[$value['product_id']]->name} на складе!");
                }

                // уменьшаем сток
                DB::table('stocks')
                ->where('product_id', $value['product_id'])
                ->where('warehouse_id', $data['warehouse_id'])
                ->decrement('stock', $value['count']);

                // добавляем запись в таблицу перемещений
                StockMovement::create([
                    'product_id' => $value['product_id'],
                    'warehouse_id' => $data['warehouse_id'],
                    'count' => -$value['count'],
                    'operation' => StockMovement::STORE,
                    'source_type' => Order::class,
                    'source_id' => $order->id,
                    'notes' => 'Товар заказан. ID:'.$order->id
                ]);

                // добавляем запись в таблицу заказанных товаров
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $value['product_id'],
                    'count' => $value['count'],
                    'price' => $products[$value['product_id']]->price,
                ]); 
        }
        
        // фиксируем
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