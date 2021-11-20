<?php

namespace App\Service;

use App\Dto\CreateOrderRequestDto;
use App\Entity\Order;
use App\Entity\Product;
use App\Entity\PromotionalCode;
use App\Event\CalculateDiscountPriceEvent;
use App\Event\CalculatePriceEvent;
use App\Event\CreateOrderEvent;
use App\Factory\OrderFactory;
use Doctrine\ORM\EntityManagerInterface;
use Psr\EventDispatcher\EventDispatcherInterface;

class CreateOrderService implements CreateOrderServiceInterface
{
    public function __construct(private OrderFactory $orderFactory, private EntityManagerInterface $manager, private EventDispatcherInterface $eventDispatcher)
    {
    }

    /**
     * {@inheritdoc}
     */
    public function createOrder(array $products, array $promotionalCodes): Order
    {
        $price = $this->calculatePriceForAllProducts($products);
        $discountPrice = 0;
        if (!empty($promotionalCodes)) {
            $discountPrice = $this->calculateDiscountPrice($products, $promotionalCodes);
        }

        $finalPrice = 0;
        if ($discountPrice < $price) {
            $finalPrice = $price - $discountPrice;
        }

        $order = $this->orderFactory->get($products, $promotionalCodes, $price, $discountPrice, $finalPrice);
        $this->manager->persist($order);
        $this->manager->flush();
        return $order;
    }

    /**
     * @param Product[] $products
     */
    private function calculatePriceForAllProducts(array $products): int
    {
        $calculatePriceEvent = new CalculatePriceEvent($products);
        $this->eventDispatcher->dispatch($calculatePriceEvent, CalculatePriceEvent::NAME);
        return $calculatePriceEvent->getPrice();
    }

    /**
     * @param Product[] $products
     * @param PromotionalCode[] $promotionalCodes
     */
    private function calculateDiscountPrice(array $products, array $promotionalCodes): int
    {
        $calculatePriceEvent = new CalculateDiscountPriceEvent($products, $promotionalCodes);
        $this->eventDispatcher->dispatch($calculatePriceEvent, CalculateDiscountPriceEvent::NAME);
        return $calculatePriceEvent->getPrice();
    }
}
