<?php

namespace App\Service;

use App\Dto\CreateOrderRequestDto;
use App\Entity\Order;

interface OrderProcessServiceInterface
{
    /**
     * Processes the Order creation request and returns the created Order.
     */
    public function processCreateOrderRequest(CreateOrderRequestDto $createOrderRequestDto): Order;
}