<?php

namespace App\Http\Controllers;

use App\Http\Resources\WarehouseResource;
use App\Models\Warehouse;
use Illuminate\Http\Request;

class WarehouseController extends Controller
{
    public function index()
    {
        // просмотр складов
        return WarehouseResource::collection(Warehouse::all());
    }
}
