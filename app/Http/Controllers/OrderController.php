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
            return OrderResource::collection($orderRequest);
        } else {
            return response()->json(['message' => 'Нет заказов'], 201);
        }
    }


    public function resume(Order $order)
    {
        // проверка. Можно возобновить только отмененный заказ
        if($order->status !== Order::CANCELED) {
            return response()->json([
                'error'=> 'Нельзя возобновить данный заказ'], 400);
            }

        // основная логика перенесена в сервис
        $response = $this->resumeService->resume($order);
        return $response;        
    }


    public function store(StoreOrderRequest $request)
    {
        $data = $request->validated();
        // основная логика перенесена в сервис
        $response = $this->storeService->store($data);
        return $response;
    }


    public function cancel(Order $order)
    {
        // если заказ неактивен, то его нельзя отменить 
        if($order->status !== Order::ACTIVE) {
            return response()->json([
                'error'=> 'Нельзя отменить данный заказ'], 400);
            }

        // основная логика перенесена в сервис
        $response = $this->cancelService->cancel($order);
        return $response;
    }


    public function completion(Order $order)
    {
        // если заказ неактивен, то его нельзя завершить
        if($order->status !== Order::ACTIVE) {
            return response()->json([
                'error'=> 'Нельзя завершить данный заказ'], 400);
            }

        // основная логика перенесена в сервис
        $response = $this->completeService->completion($order);
        return $response;
    }


    public function update(UpdateOrderRequest $request, Order $order)
    {
        // если заказ неактивен, то его нельзя обновить
        if($order->status !== Order::ACTIVE) {
            return response()->json([
                'error'=> 'Нельзя обновить данный заказ'], 400);
            }

            
        $data = $request->validated();
        // основная логика перенесена в сервис
        $response = $this->updateService->update($data, $order);
        return $response;
    }
}
