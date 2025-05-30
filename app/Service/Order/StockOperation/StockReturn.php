<?php

namespace App\Service\Order\StockOperation;

use App\Models\Order;
use Illuminate\Support\Facades\DB;

class StockReturn  {

    public function stockReturn(Order $order, $item) {
                DB::table('stocks')
                    ->where('product_id', $item->product_id)
                    ->where('warehouse_id', $order->warehouse_id)
                    ->increment('stock', $item->count);

    }
}