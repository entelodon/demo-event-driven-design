<?php

namespace App\Service;

use App\Entity\Order;

interface CreateOrderServiceInterface
{
    /**
     * Creates an order.
     *
     * @param array $products
     * @param array $promotionalCodes
     */
    public function createOrder(array $products, array $promotionalCodes): Order;
}
