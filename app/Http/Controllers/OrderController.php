<?php

namespace App\Http\Controllers;

use App\Http\Filters\OrderFilter;
use App\Http\Requests\Order\OrderFilterRequest;
use App\Http\Requests\Order\StoreOrderRequest;
use App\Http\Requests\Order\UpdateOrderRequest;
use App\Http\Resources\OrderResource;
use App\Models\Order;



class OrderController extends OrderBaseController
{

    public function index(OrderFilterRequest $request)
    {
        $data = $request->validated();
        // фильтрация (опционально)
        $filter = app()->make(OrderFilter::class,
        ['queryParams' => array_filter($data
        )]);

        // пагинация (по-дефолту - 10 заказов)
        $perPage = $request->input('per_page', 10);
        $orderRequest = Order::filter($filter)->paginate($perPage);
        
        // проверка, есть ли заказы
        if($orderRequest->count() > 0) {
            return response()->json([
                OrderResource::collection($orderRequest)]);
        } else {
            return response()->json(['message' => 'Нет заказов'], 201);
        }
    }


    public function resume(Order $order)
    {
        try {
            // проверка. Можно возобновить только отмененный заказ
            if($order->status !== Order::CANCELED) {
            return response()->json([
                'error'=> 'Нельзя возобновить данный заказ'], 400);
            }

            // основная логика перенесена в сервис
            $response = $this->resumeService->resume($order);
            return response()->json([
                'message' => 'Заказ возобновлен',
                'data' => $response,
            ], 200);
        } catch (\Exception $exception) {
            return response()->json([
                'error' => 'Ошибка при возобновлении заказа',
                'message' => $exception->getMessage(),
            ], 500);
        }        
    }


    public function store(StoreOrderRequest $request)
    {
        try {
            $data = $request->validated();
            // основная логика перенесена в сервис
            $response = $this->storeService->store($data);
            return response()->json([
                'message' => 'Заказ создан',
                'data' => $response,
            ], 200);
        } catch (\Exception $exception) {
            return response()->json([
                'error' => 'Ошибка при оформлении заказа',
                'message' => $exception->getMessage(),
            ], 500);
        }
    }


    public function cancel(Order $order)
    {
        try {
            if(!$order->isCancellable()) {
                return response()->json([
                    'error' => 'Нельзя отменить данный заказ'],
                     422);
                }
            // основная логика перенесена в сервис
            $response = $this->cancelService->cancel($order);
            return response()->json([
                'message' => 'Заказ отменен',
                'data' => $response,
            ], 200);
        } catch (\Exception $exception) {
            return response()->json([
                'error' => 'Ошибка при отмене',
                'message' => $exception->getMessage(),
            ], 500);
        }
    }


    public function completion(Order $order)
    {
        try {
            if(!$order->isCancellable()) {
                return response()->json([
                    'error' => 'Нельзя завершить данный заказ'],
                     422);
                }
            // основная логика перенесена в сервис
            $response = $this->completeService->completion($order);
            return response()->json([
                'message' => 'Заказ завершен',
                'data' => $response,
            ], 200);
        } catch (\Exception $exception) {
            return response()->json([
                'error' => 'Ошибка при завершинии заказа',
                'message' => $exception->getMessage(),
            ], 500);
        }
    }


    public function update(UpdateOrderRequest $request, Order $order)
    {
        try {
            if(!$order->isCancellable()) {
                return response()->json([
                    'error' => 'Нельзя обновить данный заказ'],
                     422);
                }
            $data = $request->validated();    
            // основная логика перенесена в сервис
            $response = $this->updateService->update($data, $order);
            return response()->json([
                'message' => 'Заказ обновлен',
                'data' => $response,
            ], 200);
        } catch (\Exception $exception) {
            return response()->json([
                'error' => 'Ошибка при обновлении заказа',
                'message' => $exception->getMessage(),
            ], 500);
        }
    }
}
