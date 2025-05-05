<?php

namespace App\Service\Order;

use App\Models\Order;
use App\Models\Stock;
use Illuminate\Support\Facades\DB;

class CancelOrderService  {

    public function cancel($order) {
        try {
            foreach ($order->items as $item) {
                DB::table('stocks')
                    ->where('product_id', $item->product_id)
                    ->where('warehouse_id', $order->warehouse_id)
                    ->increment('stock', $item->count);
            }


            $order->update([
                'status' => Order::CANCELED,
                'completed_at' => now()
            ]);

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