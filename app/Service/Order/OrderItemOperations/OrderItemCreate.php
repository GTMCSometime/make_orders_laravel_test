<?php

namespace App\Service\Order\OrderItemOperations;

use App\Models\Order;
use App\Models\OrderItem;

class OrderItemCreate  {

    public function create(Order $order, array $value, $products) {
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $value['product_id'],
                'count' => $value['count'],
                'price' => $products[$value['product_id']]->price,
            ]);
        }
}