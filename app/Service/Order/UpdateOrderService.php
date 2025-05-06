<?php

namespace App\Service\Order;

use App\Models\Order;
use App\Models\Stock;
use App\Models\StockMovement;
use Illuminate\Support\Facades\DB;

class UpdateOrderService {

    public function update(array $data, $order) {
        try {
            // ищем прошлый заказ по ID
            DB::transaction(function () use ($order, $data) {
                foreach ($order->items as $oldItem) {
                    $stock = Stock::where('product_id', $oldItem->product_id)
                                  ->where('warehouse_id', $order->warehouse_id)
                                  ->lockForUpdate()
                                  ->increment('stock', $oldItem->count);
                    
                }
                
                // добавляем запись в таблицу перемещений
                StockMovement::create([
                    'product_id' => $oldItem->product_id,
                    'warehouse_id' => $order->warehouse_id,
                    'count' => $oldItem->count,
                    'operation' => StockMovement::CANCELED,
                    'source_type' => Order::class,
                    'source_id' => $order->id,
                    'notes' => 'Возврат при отмене заказа. ID:'.$order->id
                ]);

                // удаляем прошлую запись 
                $order->items()->delete();
        
                // обновляем имя заказчика и ID склада
                $order->update([
                    'customer' => $data['customer'],
                    'warehouse_id' => $data['warehouse_id'],
                ]);


                // создаем новый заказ. Проверяем, есть ли необходимое количество товара на складе
                foreach ($data['items'] as $item) {
                    $stock = Stock::where('product_id', $item['product_id'])
                                  ->where('warehouse_id', $data['warehouse_id'])
                                  ->lockForUpdate()
                                  ->firstOrFail();
        
                                  if ($stock->stock < $item['count']) {
                                    throw new \Exception("Недостаточно товара c ID: {$item['product_id']} на складе. Актуальное количество {$stock->stock}");
                                }

                                // уменьшаем сток
                                DB::table('stocks')
                                ->where('product_id', $item['product_id'])
                                ->where('warehouse_id', $data['warehouse_id'])
                                ->decrement('stock', $item['count']);

                                // добавляем запись в таблицу движений
                                StockMovement::create([
                                    'product_id' => $item['product_id'],
                                    'warehouse_id' => $data['warehouse_id'],
                                    'count' => -$item['count'],
                                    'operation' => StockMovement::STORE,
                                    'source_type' => Order::class,
                                    'source_id' => $order->id,
                                    'notes' => 'Товар заказан. ID:'.$order->id
                                ]);

                                // создаем новый заказ
                                $order->items()->create([
                                    'product_id' => $item['product_id'],
                                    'count' => $item['count'],
                                ]);
                }
            });

            return response()->json(['message' => 'Заказ обновлён']);

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Ошибка обновления заказа',
                'message' => $e->getMessage()
            ], 400);
        }
    }
}
    

    