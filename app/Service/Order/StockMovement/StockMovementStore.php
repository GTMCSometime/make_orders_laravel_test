<?php

namespace App\Service\Order\StockMovement;

use App\Models\Order;
use App\Models\StockMovement;

class StockMovementStore {
    public function store(array $value, array $data, $order) {
            // добавляем запись в таблицу перемещений
            StockMovement::create([
                'product_id' => $value['product_id'],
                'warehouse_id' => $data['warehouse_id'],
                'count' => -$value['count'],
                'operation' => StockMovement::STORE,
                'source_type' => Order::class,
                'source_id' => $order->id,
                'notes' => 'Товар заказан. ID:'.$order->id
            ]);
    }
}
