<?php

namespace App\Http\Controllers;

use App\Http\Resources\WarehouseResource;
use App\Models\Warehouse;

class WarehouseController extends Controller
{
    public function index()
    {
        // просмотр складов
        return response()->json(
            WarehouseResource::collection(Warehouse::all())
        );
    }
}
