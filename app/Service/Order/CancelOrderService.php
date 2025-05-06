<?php

namespace App\Service\Order;

use App\Models\Order;
use App\Models\StockMovement;
use Illuminate\Support\Facades\DB;

class CancelOrderService  {

    public function cancel($order) {
        try {
            // отменяем заказ. Возвращаем товар на склад
            foreach ($order->items as $item) {
                DB::table('stocks')
                    ->where('product_id', $item->product_id)
                    ->where('warehouse_id', $order->warehouse_id)
                    ->increment('stock', $item->count);
            }

            // фиксируем в таблице движений
            StockMovement::create([
                'product_id' => $item->product_id,
                'warehouse_id' => $order->warehouse_id,
                'count' => $item->count,
                'operation' => StockMovement::CANCELED,
                'source_type' => Order::class,
                'source_id' => $order->id,
                'notes' => 'Возврат при отмене заказа. ID:'.$order->id
            ]);

            // обновляем статус и дату завершения
            $order->update([
                'status' => Order::CANCELED,
                'completed_at' => now()
            ]);
            // фиксируем
            DB::commit();

            return response()->json([
                'message' => 'Заказ успешно отменен',
                'order' => $order->load('items')
            ]);

        } catch(\Exception $exception) {

            DB::rollBack();
            return response()->json([
                'error' => 'Не удалось отменить заказ!',
                'message' => $exception->getMessage(),
            ], 500);
        }
    }
}