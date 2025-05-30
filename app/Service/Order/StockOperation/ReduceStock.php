<?php

namespace App\Service\Order\StockOperation;

use Illuminate\Support\Facades\DB;

class ReduceStock  {
    public function reduceStock(array $data, array $value) {
                DB::table('stocks')
                ->where('product_id', $value['product_id'])
                ->where('warehouse_id', $data['warehouse_id'])
                ->decrement('stock', $value['count']);
        }
}