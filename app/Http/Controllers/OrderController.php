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

        $filter = app()->make(OrderFilter::class,
        ['queryParams' => array_filter($data
        )]);

        $perPage = $request->input('per_page', 10);

        $orderRequest = Order::filter($filter)->paginate($perPage);
        
        if($orderRequest->count() > 0) {
            return OrderResource::collection($orderRequest);
        } else {
            return response()->json(['message' => 'Нет заказов'], 201);
        }
    }


    public function resume(Order $order)
    {
        if($order->status !== Order::CANCELED) {
            return response()->json([
                'error'=> 'Нельзя возобновить данный заказ'], 400);
            }
        $response = $this->resumeService->resume($order);
        return $response;        
    }


    public function store(StoreOrderRequest $request)
    {
        $data = $request->validated();
        $response = $this->storeService->store($data);
        return $response;
    }


    public function cancel(Order $order)
    {
        if($order->status !== Order::ACTIVE) {
            return response()->json([
                'error'=> 'Нельзя завершить данный заказ'], 400);
            }
        $response = $this->cancelService->cancel($order);
        return $response;
    }


    public function completion(Order $order)
    {
        if($order->status !== Order::ACTIVE) {
            return response()->json([
                'error'=> 'Нельзя завершить данный заказ'], 400);
            }
        $response = $this->completeService->completion($order);
        return $response;
    }


    public function update(UpdateOrderRequest $request, Order $order)
    {
        if($order->status !== Order::ACTIVE) {
            return response()->json([
                'error'=> 'Нельзя обновить данный заказ'], 400);
            }
        $data = $request->validated();
        $response = $this->updateService->update($data, $order);
        return $response;
    }
}
