<?php

namespace App\Service\Order\OrderOperations;

use App\Models\Order;

class OrderUpdateResume  {

    public function resume($order) {
            $order->update([
                'status' => Order::ACTIVE,
                'completed_at' => null
            ]);
        }
}