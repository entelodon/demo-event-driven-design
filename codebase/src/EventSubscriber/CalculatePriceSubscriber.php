<?php

namespace App\EventSubscriber;

use App\Event\CalculatePriceEvent;
use App\Service\CalculatePriceServiceInterface;
use JetBrains\PhpStorm\ArrayShape;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class CalculatePriceSubscriber implements EventSubscriberInterface
{
    public function __construct(private CalculatePriceServiceInterface $calculatePriceService)
    {
    }

    public function onCalculatePrice(CalculatePriceEvent $event): void
    {
        $event->setPrice($this->calculatePriceService->calculatePrice($event->getProducts()));
    }

    #[ArrayShape([CalculatePriceEvent::NAME => "string"])]
    public static function getSubscribedEvents(): array
    {
        return [
            CalculatePriceEvent::NAME => 'onCalculatePrice',
        ];
    }
}
