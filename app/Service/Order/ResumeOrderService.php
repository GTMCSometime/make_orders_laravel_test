<?php

namespace App\Service\Order;

use App\Models\Order;
use Illuminate\Support\Facades\DB;

class ResumeOrderService  {

    public function resume($order) {
        try {
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


                foreach ($order->items as $item) {
                    DB::table('stocks')
                        ->where('product_id', $item->product_id)
                        ->where('warehouse_id', $order->warehouse_id)
                        ->decrement('stock', $item->count);
                }
    

                $order->update([
                    'status' => Order::ACTIVE,
                    'completed_at' => null
                ]);

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