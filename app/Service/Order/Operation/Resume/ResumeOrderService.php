<?php

namespace App\Service\Order\Operation\Resume;

use App\Service\Order\Item\ResumeItemCheck;
use App\Service\Order\OrderOperations\OrderUpdateResume;
use App\Service\Order\StockOperation\ResumeReduceStock;
use App\Service\Order\StockMovement\StockMovementResume;
use Illuminate\Support\Facades\DB;

class ResumeOrderService  {
    public function __construct(
        private ResumeItemCheck $resumeItemCheck,
        private ResumeReduceStock $resumeReduceStock,
        private StockMovementResume $stockMovementResume,
        private OrderUpdateResume $orderUpdateResume,
    ) {}

    public function resume($order) {
        try {
            foreach ($order->items as $item) {
                // возобновляем заказ. проверяем, есть ли товар на складе
                $this->resumeItemCheck->resume($item, $order);
                // при успешной проверке обновляем сток в DB
                $this->resumeReduceStock->reduceStock($item, $order);
                // фиксируем изменения в таблице изменений
                $this->stockMovementResume->resume($item, $order);
            }
                // обновляем статус, сбрасываем дату завершения
                $this->orderUpdateResume->resume($order);
                //фиксируем
            DB::commit();

            return response()->json([
                'message' => 'Заказ успешно возобновлен',
                'order' => $order->load('items')
            ]);

        } catch(\Exception $exception) {

            DB::rollBack();
            return response()->json([
                'error' => 'Не удалось возобновить заказ!',
                'message' => $exception->getMessage(),
            ], 500);
        }
    }
}