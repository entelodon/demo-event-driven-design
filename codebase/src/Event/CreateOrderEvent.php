<?php

namespace App\Event;

use App\Entity\Order;
use App\Entity\Product;
use App\Entity\PromotionalCode;
use Symfony\Contracts\EventDispatcher\Event;

class CreateOrderEvent extends Event
{
    const NAME = 'order.create';

    private Order $order;

    /**
     * @param Product[] $products
     * @param PromotionalCode[] $promotionalCodes
     */
    public function __construct(private array $products, private array $promotionalCodes)
    {
    }

    /**
     * @return Product[]
     */
    public function getProducts(): array
    {
        return $this->products;
    }

    /**
     * @return PromotionalCode[]
     */
    public function getPromotionalCodes(): array
    {
        return $this->promotionalCodes;
    }

    public function getOrder(): Order
    {
        return $this->order;
    }

    public function setOrder(Order $order): void
    {
        $this->order = $order;
    }
}
