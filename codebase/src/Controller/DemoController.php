<?php

namespace App\Controller;

use App\Event\CreateOrderEvent;
use Psr\EventDispatcher\EventDispatcherInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DemoController extends AbstractController
{
    public function __construct(private EventDispatcherInterface $eventDispatcher)
    {
    }

    #[Route('/demo', name: 'demo')]
    public function index(): Response
    {
        $this->eventDispatcher->dispatch(new CreateOrderEvent([], []), CreateOrderEvent::NAME);
        return $this->json(null);
    }
}
