<?php

use App\Http\Controllers\WarehouseController;
use Illuminate\Support\Facades\Route;

Route::group(["prefix"=> "warehouse"], function () {
    Route::get("/", [WarehouseController::class, "index"])->name("warehouse.index");
});
