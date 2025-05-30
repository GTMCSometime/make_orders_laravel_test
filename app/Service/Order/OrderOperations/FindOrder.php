<?php

namespace App\Service\Order\OrderOperations;

use App\Models\Order;
use App\Models\Stock;

class FindOrder {

    public function find($oldItem, Order $order) {
            Stock::where('product_id', $oldItem->product_id)
                          ->where('warehouse_id', $order->warehouse_id)
                          ->lockForUpdate()
                          ->increment('stock', $oldItem->count);
    }
}
    

    