<?php

namespace App\Service\Order\StockMovement;

use App\Models\Order;
use App\Models\StockMovement;

class StockMovementCancel {
    public function cancel(Order $order, $item) {
            StockMovement::create([
                'product_id' => $item->product_id,
                'warehouse_id' => $order->warehouse_id,
                'count' => $item->count,
                'operation' => StockMovement::CANCELED,
                'source_type' => Order::class,
                'source_id' => $order->id,
                'notes' => 'Возврат при отмене заказа. ID:'.$order->id
            ]);
    }
}
