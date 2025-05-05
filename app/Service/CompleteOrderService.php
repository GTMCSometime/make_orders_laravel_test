<?php

namespace App\Service;

use App\Models\Order;
use Illuminate\Support\Facades\DB;

class CompleteOrderService  {

    public function complete($order) {
        DB::beginTransaction();
        try {
            $order->update([
                'completed_at' => now(),
                'status' => Order::COMPLETED,
            ]);
        

        DB::commit();


            return response()->json( [
                'message' => 'Заказ завершен',
                'order' => $order,
            ], 201);

        } catch(\Exception $exception) {

            DB::rollBack();
            return response()->json([
                'error' => 'Не удалось завершить заказ!',
                'message' => $exception->getMessage(),
            ], 500);
        }
    }
}