<?php

use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\WarehouseController;
use Illuminate\Support\Facades\Route;

Route::group(["prefix"=> "orders"], function () {
    Route::get("/", [OrderController::class, "index"])->name("orders.index");
    Route::post("/", [OrderController::class, "store"])->name("orders.store");
    Route::put("/{order}/update", [OrderController::class, "update"])->name("orders.update");
    Route::put("/{order}/completion", [OrderController::class, "completion"])->name("orders.completion");
    Route::put("/{order}/cancel", [OrderController::class, "cancel"])->name("orders.cancel");
});


Route::group(["prefix"=> "warehouses"], function () {
    Route::get("/", [WarehouseController::class, "index"])->name("warehouses.index");
});


Route::group(["prefix"=> "products"], function () {
    Route::get("/", [ProductController::class, "index"])->name("products.index");
});
