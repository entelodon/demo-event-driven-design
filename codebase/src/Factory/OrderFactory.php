<?php

namespace App\Factory;

use App\Entity\Order;
use App\Entity\Product;
use App\Entity\PromotionalCode;

class OrderFactory
{
    /**
     * @param Product[] $products
     * @param PromotionalCode[] $promotionalCodes
     * @param int $price
     * @param int $discountPrice
     * @param int $finalPrice
     */
    public function get(array $products, array $promotionalCodes, int $price, int $discountPrice, int $finalPrice): Order
    {
        $order = new Order();

        foreach ($products as $product) {
            $order->addProduct($product);
        }

        foreach ($promotionalCodes as $promotionalCode) {
            $order->addPromotionalCode($promotionalCode);
        }

        $order->setPrice($price);
        $order->setFinalPrice($finalPrice);
        $order->setDiscountPrice($discountPrice);
        return $order;
    }
}
