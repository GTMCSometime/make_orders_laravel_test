<?php

namespace App\Service\Order\Operation\Update;

use App\Service\Order\OrderOperations\FindOrder;
use App\Service\Order\Operation\Store\StoreOrderService;
use App\Service\Order\StockMovement\StockMovementCancel;
use Illuminate\Support\Facades\DB;

class UpdateOrderService {
    public function __construct(
        private FindOrder $findOrder,
        private StockMovementCancel $stockMovementCancel,
        private StoreOrderService $storeOrderService,
    ) {}

    public function update(array $data, $order) {
        // ищем прошлый заказ по ID
        DB::transaction(function () use ($order, $data) {
            foreach ($order->items as $oldItem) {
                $this->findOrder->find($oldItem, $order);
            
                // добавляем запись в таблицу перемещений
                $this->stockMovementCancel->cancel($order, $oldItem);
                }
                // удаляем прошлую запись 
                $order->items()->delete();
            
                // обновляем имя заказчика и ID склада
                $order->update([
                    'customer' => $data['customer'],
                    'warehouse_id' => $data['warehouse_id'],
                ]);
            
                // создаем новый заказ. Проверяем, есть ли необходимое количество товара на складе
                $this->storeOrderService->store($data);
        });
            return $order->fresh();
    }
}
    

    