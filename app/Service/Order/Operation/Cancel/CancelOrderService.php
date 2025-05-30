<?php

namespace App\Service\Order\Operation\Cancel;

use App\Http\Resources\OrderResource;
use App\Service\Order\OrderOperations\OrderUpdate;
use App\Service\Order\OrderOperations\OrderUpdateCanceled;
use App\Service\Order\StockOperation\StockReturn;
use App\Service\Order\StockMovement\StockMovementCancel;
use Illuminate\Support\Facades\DB;

class CancelOrderService  {

    public function __construct(
        private StockReturn $stockReturn,
        private StockMovementCancel $stockMovementCancel,
        private OrderUpdateCanceled $orderUpdateCanceled) {}

    public function cancel($order) {
        DB::beginTransaction();
        try {
            foreach ($order->items as $item) {
            $this->stockReturn->stockReturn($order, $item);
            $this->stockMovementCancel->cancel($order, $item);
            $cancelOrder = $this->orderUpdateCanceled->cancel($order);
            }
            DB::commit();

            return new OrderResource($cancelOrder);

        } catch(\Exception $exception) {

            DB::rollBack();
            throw $exception;
        }
    }
}