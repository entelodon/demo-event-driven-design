<?php

namespace App\Service;

use App\Entity\PromotionalCode;

class CalculatePromotionalCodeDiscountPriceService implements CalculatePromotionalCodeDiscountPriceServiceInterface
{
    const PERCENTAGE_DIVIDER = 100;

    /**
     * {@inheritdoc}
     */
    public function calculatePromotionalCodeDiscountPrice(array $products, PromotionalCode $promotionalCode): int
    {
        $discountPrice = 0;
        $discountAmount = $promotionalCode->getAmount();
        foreach ($products as $product) {
            if ($promotionalCode->getTypes()->contains($product->getType())) {
                if (!$promotionalCode->getExactAmount()) {
                    $discountPrice += $this->calculatePercentage($promotionalCode->getAmount(), $product->getPrice());
                    continue;
                }

                if ($product->getPrice() >= $discountAmount) {
                    $discountPrice += $discountAmount;
                    return $discountPrice;
                }
                $discountAmount -= $product->getPrice();
                $discountPrice += $product->getPrice();
            }
        }

        return floor($discountPrice);
    }

    private function calculatePercentage(int $amount, int $productPrice): int
    {
        $percentage = $amount / self::PERCENTAGE_DIVIDER;
        return $productPrice * $percentage;
    }
}
