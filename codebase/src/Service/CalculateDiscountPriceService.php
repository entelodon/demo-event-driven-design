<?php

namespace App\Service;

use App\Entity\Product;
use App\Entity\PromotionalCode;
use App\Event\CalculatePromotionalCodeDiscountPriceEvent;
use Psr\EventDispatcher\EventDispatcherInterface;

class CalculateDiscountPriceService implements CalculateDiscountPriceServiceInterface
{
    public function __construct(private EventDispatcherInterface $eventDispatcher)
    {
    }

    /**
     * {@inheritdoc}
     */
    public function calculateDiscountPrice(array $products, array $promotionalCodes): int
    {
        $price = 0;
        foreach ($promotionalCodes as $promotionalCode) {
            $price += $this->calculatePromotionalCodeDiscount($products, $promotionalCode);
        }
        return $price;
    }

    /**
     * @param Product[] $products
     * @param PromotionalCode $promotionalCode
     */
    private function calculatePromotionalCodeDiscount(array $products, PromotionalCode $promotionalCode): int
    {
        $calculatePromotionalCodeDiscountPriceEvent = new CalculatePromotionalCodeDiscountPriceEvent($products, $promotionalCode);
        $this->eventDispatcher->dispatch($calculatePromotionalCodeDiscountPriceEvent, CalculatePromotionalCodeDiscountPriceEvent::NAME);
        return $calculatePromotionalCodeDiscountPriceEvent->getPrice();
    }
}
