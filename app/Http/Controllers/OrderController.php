<?php

namespace App\Http\Controllers;

use App\Http\Filters\OrderFilter;
use App\Http\Requests\OrderFilterRequest;
use App\Http\Resources\OrderResource;
use App\Models\Order;
use Illuminate\Http\Request;


class OrderController extends Controller
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
            return response()->json(['message' => 'Нет записей'], 200);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
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
    public function edit(Order $order)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Order $order)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Order $order)
    {
        //
    }
}
