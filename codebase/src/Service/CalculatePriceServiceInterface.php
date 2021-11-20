<?php

namespace App\Service;

use App\Entity\Product;

interface CalculatePriceServiceInterface
{
    /**
     * Calculates the prices of given products.
     *
     * @param Product[] $products
     */
    public function calculatePrice(array $products): int;
}
