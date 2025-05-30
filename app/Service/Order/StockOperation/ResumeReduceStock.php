<?php

namespace App\Service\Order\StockOperation;

use App\Models\Order;
use Illuminate\Support\Facades\DB;

class ResumeReduceStock  {
    public function reduceStock(Order $order, $item) {
                DB::table('stocks')
                ->where('product_id', $item->product_id)
                ->where('warehouse_id', $order->warehouse_id)
                ->decrement('stock', $item->count);
        }
}