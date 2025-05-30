<?php

namespace App\Service\Order\Item;

use App\Models\Stock;

class StoreItemCheck  {
    public function check(array $data, array $value, $products) {
                $stock = Stock::where('product_id', $value['product_id'])
                ->where('warehouse_id', $data['warehouse_id'])
                ->lockForUpdate()
                ->first();
                
                if ($stock->stock < $value['count']) {
                    throw new \Exception("Недостаточно товара {$products[$value['product_id']]->name} на складе!");
                }
    }
}