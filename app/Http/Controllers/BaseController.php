<?php

namespace App\Http\Controllers;

use App\Service\StoreOrderService;

abstract class BaseController extends Controller
{
    public $service;

    public function __construct(StoreOrderService $service) {
        $this->service = $service;
    }
}
