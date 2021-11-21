<?php

namespace App\Service;

use App\Entity\Order;
use App\Exception\OrderNotFoundException;
use App\Repository\OrderRepository;

class FindOrderService implements FindOrderServiceInterface
{
    public function __construct(private OrderRepository $orderRepository)
    {
    }

    /**
     * {@inheritdoc}
     */
    public function findById(int $orderId): Order
    {
        $order = $this->orderRepository->find($orderId);
        if ($order === null) {
            throw new OrderNotFoundException($orderId);
        }
        return $order;
    }
}
