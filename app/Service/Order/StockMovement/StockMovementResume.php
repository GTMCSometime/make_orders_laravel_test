<?php

namespace App\Service\Order\StockMovement;

use App\Models\Order;
use App\Models\StockMovement;

class StockMovementResume {
    public function resume($item, Order $order) {
        StockMovement::create([
                    'product_id' => $item->product_id,
                    'warehouse_id' => $order->warehouse_id,
                    'count' => $item->count,
                    'operation' => StockMovement::RESUME,
                    'source_type' => Order::class,
                    'source_id' => $order->id,
                    'notes' => 'Возобновление заказа. ID:'.$order->id
                ]);
    }
}
