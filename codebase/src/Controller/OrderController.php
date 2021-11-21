<?php

namespace App\Controller;

use App\Controller\Constant\ControllerConstants;
use App\Entity\Order;
use App\Exception\CreateOrderDtoValidationException;
use App\Exception\InvalidRequestException;
use App\Exception\NoProductsSuppliedException;
use App\Exception\OrderNotFoundException;
use App\Factory\CreatedOrderResponseFactory;
use App\Factory\CreateOrderRequestFactory;
use App\Service\CreateOrderRequestDtoValidationServiceInterface;
use App\Service\FindOrderServiceInterface;
use App\Service\OrderProcessServiceInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class OrderController extends AbstractController
{
    public function __construct(private FindOrderServiceInterface $findOrderService, private CreateOrderRequestFactory $createOrderRequestFactory, private CreatedOrderResponseFactory $createdOrderResponseFactory, private CreateOrderRequestDtoValidationServiceInterface $createOrderRequestDtoValidationService, private OrderProcessServiceInterface $orderProcessService)
    {
    }

    #[Route('/order/{orderId}', name: 'order.index', methods: ['GET'])]
    public function index(Request $request, int $orderId): Response
    {
        try {
            $orderResponse = $this->createdOrderResponseFactory->createFromOrder($this->findOrderService->findById($orderId));
        } catch (OrderNotFoundException $exception) {
            return $this->json([
                ControllerConstants::RESPONSE_ERROR => $exception->getMessage(),
            ], 400);
        }
        return $this->json([
            ControllerConstants::RESPONSE_MESSAGE => $orderResponse,
        ]);
    }

    #[Route('/order', name: 'order.create', methods: ['POST'])]
    public function create(Request $request): Response
    {
        if ($request->getContentType() !== ControllerConstants::JSON_REQUEST) {
            return $this->json([
                ControllerConstants::RESPONSE_ERROR => 'Only `application/json` requests are accepted!',
            ], 400);
        }
        try {
            $createOrderRequestDto = $this->createOrderRequestFactory->createFromRequest($request);
            $this->createOrderRequestDtoValidationService->validateCreateOrderDtoData($createOrderRequestDto);
            $createdOrderResponse = $this->createdOrderResponseFactory->createFromOrder($this->orderProcessService->processCreateOrderRequest($createOrderRequestDto));
        } catch (InvalidRequestException | NoProductsSuppliedException | CreateOrderDtoValidationException $exception) {
            return $this->json([
                ControllerConstants::RESPONSE_ERROR => $exception->getMessage(),
            ], 400);
        }

        return $this->json([
            ControllerConstants::RESPONSE_MESSAGE => $createdOrderResponse,
        ]);
    }
}
