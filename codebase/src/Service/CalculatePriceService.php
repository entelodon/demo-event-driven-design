<?php

namespace App\Service;

use JetBrains\PhpStorm\Pure;

class CalculatePriceService implements CalculatePriceServiceInterface
{

    /**
     * {@inheritdoc}
     */
    #[Pure]
    public function calculatePrice(array $products): int
    {
        $price = 0;
        foreach ($products as $product) {
            $price += $product->getPrice();
        }
        return $price;
    }
}
