<?php

namespace App\Service\Order\Operation\Store;

use App\Models\Product;
use App\Service\Order\Item\StoreItemCheck;
use App\Service\Order\OrderItemOperations\OrderItemCreate;
use App\Service\Order\OrderOperations\OrderCreate;
use App\Service\Order\StockOperation\ReduceStock;
use App\Service\Order\StockMovement\StockMovementStore;
use Illuminate\Support\Facades\DB;

class StoreOrderService  {
    public function __construct(
        private StockMovementStore $stockMovementStore,
        private OrderCreate $orderCreate,
        private StoreItemCheck $itemCheck,
        private ReduceStock $reduceStock,
        private OrderItemCreate $orderItemCreate,
        ){}

    public function store(array $data) {
        DB::beginTransaction();
        try {
            // создаем заказ
            $order = $this->orderCreate->create($data);


            // получаем все товары в коллекцию
            $productIds = collect($data['items'])->pluck('product_id');
            $products = Product::whereIn('id', $productIds)->get()->keyBy('id');

            // проверяем, можно ли заказать товар
            foreach ($data['items'] as $value) {
                // проверям возможность заказа
                $this->itemCheck->check($data, $value, $products);
                // уменьшаем сток
                $this->reduceStock->reduceStock($data, $value);
                // записываем в таблицу движений
                $this->stockMovementStore->store($value, $data, $order);
                // добавляем запись в таблицу заказанных товаров
                $this->orderItemCreate->create($order, $value, $products); 
        }
        // фиксируем
        DB::commit();
            return $order;
        } catch(\Exception $exception) {
            DB::rollBack();
            throw $exception;
        }
    }
}