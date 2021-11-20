<?php

namespace App\Event;

use App\Entity\Product;
use App\Entity\PromotionalCode;
use Symfony\Contracts\EventDispatcher\Event;

class CalculateDiscountPriceEvent extends Event
{
    const NAME = 'discount_price.calculate';

    private int $price = 0;

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

    public function getPrice(): int
    {
        return $this->price;
    }

    public function setPrice(int $price): void
    {
        $this->price = $price;
    }
}
