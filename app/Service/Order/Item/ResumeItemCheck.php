<?php

namespace App\Service\Order\Item;

use Illuminate\Support\Facades\DB;

class ResumeItemCheck  {

    public function resume($item, $order) {
            $stock = DB::table('stocks')
                ->where('product_id', $item->product_id)
                ->where('warehouse_id', $order->warehouse_id)
                ->lockForUpdate()
                ->first();
                if (!$stock) {
                    throw new \Exception("Товар ID: {$item->product_id} отсутствует на складе");
                }
                if ($stock->stock < $item->count) {
                    throw new \Exception(
                        "Недостаточно товара с ID: {$item->product_id}. " .
                        "Доступно: {$stock->stock}, требуется: {$item->count}"
                    );
                }
            }

}