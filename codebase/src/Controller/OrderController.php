<?php

namespace App\Controller;

use App\Controller\Constant\ControllerConstants;
use App\Exception\CreateOrderDtoValidationException;
use App\Exception\InvalidRequestException;
use App\Factory\CreatedOrderResponseFactory;
use App\Factory\CreateOrderRequestFactory;
use App\Service\CreateOrderRequestDtoValidationServiceInterface;
use App\Service\OrderProcessServiceInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class OrderController extends AbstractController
{
    public function __construct(private CreateOrderRequestFactory $createOrderRequestFactory, private CreatedOrderResponseFactory $createdOrderResponseFactory, private CreateOrderRequestDtoValidationServiceInterface $createOrderRequestDtoValidationService, private OrderProcessServiceInterface $orderProcessService)
    {
    }

    #[Route('/order', name: 'order', methods: ['POST'])]
    public function index(Request $request): Response
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
        } catch (InvalidRequestException|CreateOrderDtoValidationException $exception) {
            return $this->json([
                ControllerConstants::RESPONSE_ERROR => $exception->getMessage(),
            ], 400);
        }

        return $this->json([
            ControllerConstants::RESPONSE_MESSAGE => $createdOrderResponse,
        ]);
    }
}
