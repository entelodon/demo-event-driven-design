<?php

namespace App\Service;

use App\Entity\Order;
use App\Factory\OrderFactory;
use Doctrine\Persistence\ObjectManager;

class CreateOrderService implements CreateOrderServiceInterface
{
    public function __construct(private OrderFactory $orderFactory, private ObjectManager $manager)
    {
    }

    /**
     * {@inheritdoc}
     */
    public function createOrder(array $products, array $promotionalCodes, int $price, int $discountPrice, int $finalPrice): Order
    {
        $order = $this->orderFactory->get($products, $promotionalCodes, $price, $discountPrice, $finalPrice);
        $this->manager->persist($order);
        $this->manager->flush();
        return $order;
    }
}
