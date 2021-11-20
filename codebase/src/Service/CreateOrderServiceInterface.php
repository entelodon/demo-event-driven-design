<?php

namespace App\Service;

use App\Entity\Order;
use App\Entity\Product;
use App\Event\CreateOrderEvent;

interface CreateOrderServiceInterface
{
    /**
     * Creates an order.
     *
     * @param array $products
     * @param array $promotionalCodes
     * @param int $price
     * @param int $discountPrice
     * @param int $finalPrice
     */
    public function createOrder(array $products, array $promotionalCodes, int $price, int $discountPrice, int $finalPrice): Order;
}
