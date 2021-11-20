<?php

namespace App\Service;

use App\Entity\Product;
use App\Entity\PromotionalCode;

interface CalculatePromotionalCodeDiscountPriceServiceInterface
{
    /**
     * Calculates the discount price of the given products by the given promotional codes.
     *
     * @param Product[] $products
     * @param PromotionalCode $promotionalCode
     */
    public function calculatePromotionalCodeDiscountPrice(array $products, PromotionalCode $promotionalCode): int;
}
