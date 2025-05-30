<?php

namespace App\Service\Order\OrderOperations;

use App\Models\Order;

class OrderCreate  {

    public function create(array $data) {
            $order = Order::create([
                'customer' => $data['customer'],
                'created_at' => now(),
                'warehouse_id' => $data['warehouse_id'],
                'status' => Order::ACTIVE,
            ]);
            return $order;
        }
}