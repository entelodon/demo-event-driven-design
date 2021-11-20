<?php

namespace App\Event;

use App\Entity\Product;
use Symfony\Contracts\EventDispatcher\Event;

class CalculatePriceEvent extends Event
{
    const NAME = 'price.calculate';

    private int $price = 0;

    /**
     * @param Product[] $products
     */
    public function __construct(private array $products)
    {
    }

    /**
     * @return Product[]
     */
    public function getProducts(): array
    {
        return $this->products;
    }

    public function getPrice(): int
    {
        return $this->price;
    }

    public function setPrice(int $price): void
    {
        $this->price = $price;
    }
}
