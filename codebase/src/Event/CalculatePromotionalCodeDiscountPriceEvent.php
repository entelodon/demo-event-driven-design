<?php

namespace App\Event;

use App\Entity\Product;
use App\Entity\PromotionalCode;
use Symfony\Contracts\EventDispatcher\Event;

class CalculatePromotionalCodeDiscountPriceEvent extends Event
{
    const NAME = 'promotional_code.discount_price.calculate';

    private int $price = 0;

    /**
     * @param Product[] $products
     */
    public function __construct(private array $products, private PromotionalCode $promotionalCode)
    {
    }

    /**
     * @return Product[]
     */
    public function getProducts(): array
    {
        return $this->products;
    }

    public function getPromotionalCode(): PromotionalCode
    {
        return $this->promotionalCode;
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
