<?php

namespace App\Factory;

use App\Dto\CreatedOrderResponseDto;
use App\Entity\Order;
use App\Entity\Product;
use App\Entity\PromotionalCode;
use JetBrains\PhpStorm\Pure;

class CreatedOrderResponseFactory
{
    #[Pure]
    public function createFromOrder(Order $order): CreatedOrderResponseDto
    {
        $productNames = $this->getProductNames($order->getProducts()->toArray());
        $promotionalCodes = $this->getPromotionalCodes($order->getPromotionalCodes()->toArray());
        return new CreatedOrderResponseDto($productNames, $promotionalCodes, $order->getPrice(), $order->getDiscountPrice(), $order->getFinalPrice());
    }

    /**
     * @param Product[] $products
     * @return string[]
     */
    #[Pure]
    private function getProductNames(array $products): array
    {
        $result = [];
        foreach ($products as $product) {
            $result[] = $product->getName();
        }
        return $result;
    }

    /**
     * @param PromotionalCode[] $promotionalCodes
     * @return string[]
     */
    #[Pure]
    private function getPromotionalCodes(array $promotionalCodes): array
    {
        $result = [];
        foreach ($promotionalCodes as $promotionalCode) {
            $result[] = $promotionalCode->getCode();
        }
        return $result;
    }
}
