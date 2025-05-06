<?php

namespace App\Service\Order;

use App\Models\Order;
use App\Models\StockMovement;
use Illuminate\Support\Facades\DB;

class ResumeOrderService  {

    public function resume($order) {
        try {
            // возобновляем заказ. проверяем, есть ли товар на складе
            foreach ($order->items as $item) {
                $stock = DB::table('stocks')
                    ->where('product_id', $item->product_id)
                    ->where('warehouse_id', $order->warehouse_id)
                    ->lockForUpdate()
                    ->first();

                    if (!$stock) {
                        throw new \Exception("Товар ID: {$item->product_id} отсутствует на складе");
                    }


                    if ($stock->stock < $item->count) {
                        throw new \Exception(
                            "Недостаточно товара с ID: {$item->product_id}. " .
                            "Доступно: {$stock->stock}, требуется: {$item->count}"
                        );
                    }
                }

                // при успешной проверке обновляем сток в DB
                foreach ($order->items as $item) {
                    DB::table('stocks')
                        ->where('product_id', $item->product_id)
                        ->where('warehouse_id', $order->warehouse_id)
                        ->decrement('stock', $item->count);
                }


                // фиксируем изменения в таблице изменений
                StockMovement::create([
                    'product_id' => $item->product_id,
                    'warehouse_id' => $order->warehouse_id,
                    'count' => $item->count,
                    'operation' => StockMovement::RESUME,
                    'source_type' => Order::class,
                    'source_id' => $order->id,
                    'notes' => 'Возобновление заказа. ID:'.$order->id
                ]);
    
                // обновляем статус, сбрасываем дату завершения
                $order->update([
                    'status' => Order::ACTIVE,
                    'completed_at' => null
                ]);
                //фиксируем
            DB::commit();

            return response()->json([
                'message' => 'Заказ успешно возобновлен',
                'order' => $order->load('items')
            ]);

        } catch(\Exception $exception) {

            DB::rollBack();
            return response()->json([
                'error' => 'Не удалось возобновить заказ!',
                'message' => $exception->getMessage(),
            ], 500);
        }
    }
}