<?php

namespace App\EventSubscriber;

use App\Event\CalculateDiscountPriceEvent;
use App\Service\CalculateDiscountPriceServiceInterface;
use JetBrains\PhpStorm\ArrayShape;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class CalculateDiscountPriceSubscriber implements EventSubscriberInterface
{
    public function __construct(private CalculateDiscountPriceServiceInterface $calculateDiscountPriceService)
    {
    }

    public function onCalculateDiscountPrice(CalculateDiscountPriceEvent $event): void
    {
        $event->setPrice($this->calculateDiscountPriceService->calculateDiscountPrice($event->getProducts(), $event->getPromotionalCodes()));
    }

    #[ArrayShape([CalculateDiscountPriceEvent::NAME => "string"])]
    public static function getSubscribedEvents(): array
    {
        return [
            CalculateDiscountPriceEvent::NAME => 'onCalculateDiscountPrice',
        ];
    }
}
