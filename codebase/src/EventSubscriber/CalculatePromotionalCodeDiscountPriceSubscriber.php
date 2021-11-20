<?php

namespace App\EventSubscriber;

use App\Event\CalculateDiscountPriceEvent;
use App\Event\CalculatePriceEvent;
use App\Event\CalculatePromotionalCodeDiscountPriceEvent;
use App\Service\CalculateDiscountPriceServiceInterface;
use App\Service\CalculatePriceServiceInterface;
use App\Service\CalculatePromotionalCodeDiscountPriceService;
use JetBrains\PhpStorm\ArrayShape;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class CalculatePromotionalCodeDiscountPriceSubscriber implements EventSubscriberInterface
{
    public function __construct(private CalculatePromotionalCodeDiscountPriceService $calculatePromotionalCodeDiscountPriceService)
    {
    }

    public function onCalculateDiscountPrice(CalculatePromotionalCodeDiscountPriceEvent $event): void
    {
        $event->setPrice($this->calculatePromotionalCodeDiscountPriceService->calculatePromotionalCodeDiscountPrice($event->getProducts(), $event->getPromotionalCode()));
    }

    #[ArrayShape([CalculatePromotionalCodeDiscountPriceEvent::NAME => "string"])]
    public static function getSubscribedEvents(): array
    {
        return [
            CalculatePromotionalCodeDiscountPriceEvent::NAME => 'onCalculateDiscountPrice',
        ];
    }
}
