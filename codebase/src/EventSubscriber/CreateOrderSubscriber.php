<?php

namespace App\EventSubscriber;

use App\Event\CalculatePriceEvent;
use App\Event\CreateOrderEvent;
use App\Service\CreateOrderServiceInterface;
use JetBrains\PhpStorm\ArrayShape;
use Psr\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class CreateOrderSubscriber implements EventSubscriberInterface
{
    public function __construct(private CreateOrderServiceInterface $createOrderService, private EventDispatcherInterface $eventDispatcher)
    {
    }

    public function onOrderCreate(CreateOrderEvent $event): void
    {
        $calculatePriceEvent = new CalculatePriceEvent($event->getProducts());
        $this->eventDispatcher->dispatch($calculatePriceEvent, CalculatePriceEvent::NAME);
        $event->setOrder($this->createOrderService->createOrder(
            $event->getProducts(),
            $event->getPromotionalCodes(),
            $calculatePriceEvent->getPrice(),
            $calculatePriceEvent->getPrice(),
            0
        ));
    }

    #[ArrayShape([CreateOrderEvent::NAME => "string"])]
    public static function getSubscribedEvents(): array
    {
        return [
            CreateOrderEvent::NAME => 'onOrderCreate',
        ];
    }
}
