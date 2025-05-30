<?php

namespace App\Http\Controllers;

use App\Service\Order\Operation\Cancel\CancelOrderService;
use App\Service\Order\Operation\Complete\CompleteOrderService;
use App\Service\Order\Operation\Resume\ResumeOrderService;
use App\Service\Order\Operation\Store\StoreOrderService;
use App\Service\Order\Operation\Update\UpdateOrderService;

abstract class OrderBaseController extends Controller
{
    public $storeService;
    public $updateService;
    public $completeService;
    public $cancelService;
    public $resumeService;


    public function __construct(
        StoreOrderService $storeService, 
        UpdateOrderService $updateService,
        CompleteOrderService $completeService,
        CancelOrderService $cancelService,
        ResumeOrderService $resumeService,
        ) {

        $this->storeService = $storeService;
        $this->updateService = $updateService;
        $this->completeService = $completeService;
        $this->cancelService = $cancelService;
        $this->resumeService = $resumeService;
        
    }
}
