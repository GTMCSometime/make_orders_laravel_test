<?php

use App\Http\Controllers\OrderController;
use App\Http\Controllers\WarehouseController;
use Illuminate\Support\Facades\Route;

Route::group(["prefix"=> "orders"], function () {
    Route::get("/", [OrderController::class, "index"])->name("orders.index");
});
