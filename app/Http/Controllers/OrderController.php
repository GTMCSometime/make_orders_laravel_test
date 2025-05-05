<?php

namespace App\Http\Controllers;

use App\Http\Filters\OrderFilter;
use App\Http\Requests\OrderFilterRequest;
use App\Http\Requests\StoreOrderRequest;
use App\Http\Requests\UpdateOrderRequest;
use App\Http\Resources\OrderResource;
use App\Models\Order;



class OrderController extends BaseController
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


    public function create()
    {
        
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreOrderRequest $request)
    {
        $data = $request->validated();
        $response = $this->storeService->store($data);
        return $response;
    }

    /**
     * Display the specified resource.
     */
    public function show(Order $order)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function complete(Order $order)
    {
        if($order->status !== Order::ACTIVE) {
            return response()->json([
                'error'=> 'Нельзя отменить данный заказ'], 400);
            }
        $response = $this->completeService->complete($order);
        return $response;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateOrderRequest $request, Order $order)
    {
        if($order->status !== Order::ACTIVE || $order->status !== Order::COMPLETED) {
            return response()->json([
                'error'=> 'Нельзя завершить данный заказ'], 400);
            }
        $data = $request->validated();
        $response = $this->updateService->update($data, $order);
        return $response;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Order $order)
    {
        //
    }
}
