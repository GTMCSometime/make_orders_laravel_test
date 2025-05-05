<?php

namespace App\Http\Controllers;

use App\Service\Order\CancelOrderService;
use App\Service\Order\CompleteOrderService;
use App\Service\Order\StoreOrderService;
use App\Service\Order\UpdateOrderService;

abstract class OrderBaseController extends Controller
{
    public $storeService;
    public $updateService;
    public $completeService;
    public $cancelService;


    public function __construct(
        StoreOrderService $storeService, 
        UpdateOrderService $updateService,
        CompleteOrderService $completeService,
        CancelOrderService $cancelService) {

        $this->storeService = $storeService;
        $this->updateService = $updateService;
        $this->completeService = $completeService;
        $this->cancelService = $cancelService;
        
    }
}
