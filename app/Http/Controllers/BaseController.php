<?php

namespace App\Http\Controllers;

use App\Service\CompleteOrderService;
use App\Service\StoreOrderService;
use App\Service\UpdateOrderService;

abstract class BaseController extends Controller
{
    public $storeService;
    public $updateService;
    public $completeService;


    public function __construct(
        StoreOrderService $storeService, 
        UpdateOrderService $updateService,
        CompleteOrderService $completeService) {
        $this->storeService = $storeService;
        $this->updateService = $updateService;
        $this->completeService = $completeService;
    }
}
