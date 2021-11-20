<?php

namespace App\Service;

use App\Entity\Product;
use App\Entity\PromotionalCode;

interface CalculateDiscountPriceServiceInterface
{
    /**
     * Calculates the discount price of the given products by the given promotional codes.
     *
     * @param Product[] $products
     * @param PromotionalCode[] $promotionalCodes
     */
    public function calculateDiscountPrice(array $products, array $promotionalCodes): int;
}
