<?php

namespace App\EventSubscriber;

use App\Event\CreateOrderEvent;
use App\Service\CreateOrderServiceInterface;
use JetBrains\PhpStorm\ArrayShape;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class CreateOrderSubscriber implements EventSubscriberInterface
{
    public function __construct(private CreateOrderServiceInterface $createOrderService)
    {
    }

    public function onOrderCreate(CreateOrderEvent $event): void
    {
        $event->setOrder($this->createOrderService->createOrder(
            $event->getProducts(),
            $event->getPromotionalCodes()
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
