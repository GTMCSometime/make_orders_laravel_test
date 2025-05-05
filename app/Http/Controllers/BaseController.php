<?php

namespace App\Http\Controllers;

use App\Service\StoreOrderService;
use App\Service\UpdateOrderService;

abstract class BaseController extends Controller
{
    public $storeService;
    public $updateService;


    public function __construct(StoreOrderService $storeService, UpdateOrderService $updateService) {
        $this->storeService = $storeService;
        $this->updateService = $updateService;
    }
}
