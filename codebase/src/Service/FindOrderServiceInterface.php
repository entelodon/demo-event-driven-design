<?php

namespace App\Service;

use App\Entity\Order;
use App\Exception\OrderNotFoundException;

interface FindOrderServiceInterface
{
    /**
     * Finds an order by provided id.
     * @throws OrderNotFoundException
     */
    public function findById(int $orderId): Order;
}
