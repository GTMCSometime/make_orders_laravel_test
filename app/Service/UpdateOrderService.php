<?php

namespace App\Service;

use App\Models\Stock;
use Illuminate\Support\Facades\DB;

class UpdateOrderService {

    public function update(array $data, $order) {
        try {
            DB::transaction(function () use ($order, $data) {
                foreach ($order->items as $oldItem) {
                    $stock = Stock::where('product_id', $oldItem->product_id)
                                  ->where('warehouse_id', $order->warehouse_id)
                                  ->lockForUpdate()
                                  ->increment('stock', $oldItem->count);
                    
                }


                $order->items()->delete();
        

                $order->update([
                    'customer' => $data['customer'],
                    'warehouse_id' => $data['warehouse_id'],
                ]);



                // 4. Добавить новые позиции и списать stock
                foreach ($data['items'] as $item) {
                    $stock = Stock::where('product_id', $item['product_id'])
                                  ->where('warehouse_id', $data['warehouse_id'])
                                  ->lockForUpdate()
                                  ->firstOrFail();
        
                                  if ($stock->stock < $item['count']) {
                                    throw new \Exception("Недостаточно товара ID: {$item['product_id']} на складе");
                                }


                                DB::table('stocks')
                                ->where('product_id', $item['product_id'])
                                ->where('warehouse_id', $data['warehouse_id'])
                                ->decrement('stock', $item['count']);

                                $order->items()->create([
                                    'product_id' => $item['product_id'],
                                    'count' => $item['count'],
                                ]);
                }
            });

            return response()->json(['message' => 'Заказ обновлён']);

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Ошибка обновления заказа',
                'message' => $e->getMessage()
            ], 400);
        }
    }
}
    

    