<?php

namespace App\Service;

use App\Dto\CreateOrderRequestDto;
use App\Entity\Order;
use App\Entity\Product;
use App\Entity\PromotionalCode;
use App\Event\CreateOrderEvent;
use Psr\EventDispatcher\EventDispatcherInterface;

class OrderProcessService implements OrderProcessServiceInterface
{
    public function __construct(private EventDispatcherInterface $eventDispatcher, private ProductServiceInterface $productService, private PromotionalCodeServiceInterface $promotionalCodeService)
    {
    }

    /**
     * {@inheritdoc}
     */
    public function processCreateOrderRequest(CreateOrderRequestDto $createOrderRequestDto): Order
    {
        $orderEvent = new CreateOrderEvent($this->getProductsByIds($createOrderRequestDto->getProducts()), $this->getPromotionalCodesByCodes($createOrderRequestDto->getPromotionalCodes()));
        $this->eventDispatcher->dispatch($orderEvent, CreateOrderEvent::NAME);
        return $orderEvent->getOrder();
    }

    /**
     * @param int[] $productIds
     * @return Product[]
     */
    private function getProductsByIds(array $productIds): array
    {
        $result = [];
        foreach ($productIds as $productId) {
            $result[] = $this->productService->findProductById($productId);
        }
        return $result;
    }

    /**
     * @param string[] $codes
     * @return PromotionalCode[]
     */
    private function getPromotionalCodesByCodes(array $codes): array
    {
        $result = [];
        foreach ($codes as $code) {
            $result[] = $this->promotionalCodeService->findPromotionalCodeByCode($code);
        }
        return $result;
    }
}
