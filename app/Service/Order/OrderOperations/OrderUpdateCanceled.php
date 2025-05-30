<?php

namespace App\Service\Order\OrderOperations;

use App\Models\Order;

class OrderUpdateCanceled  {

    public function cancel($order) {
            $order->update([
                'status' => Order::CANCELED,
                'completed_at' => now()
            ]);
            return $order->load('items');
        }
}