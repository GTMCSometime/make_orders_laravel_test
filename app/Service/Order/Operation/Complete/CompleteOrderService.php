<?php

namespace App\Service\Order\Operation\Complete;

use App\Models\Order;
use Illuminate\Support\Facades\DB;

class CompleteOrderService  {

    public function completion($order) {
        DB::beginTransaction();
        try {
            // завершаем заказ
            $order->update([
                'completed_at' => now(),
                'status' => Order::COMPLETED,
            ]);
        DB::commit();
        return $order;
        } catch(\Exception $exception) {
            DB::rollBack();
            throw $exception;
        }
    }
}