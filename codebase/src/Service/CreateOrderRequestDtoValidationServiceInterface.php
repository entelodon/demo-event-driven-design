<?php

namespace App\Service;

use App\Dto\CreateOrderRequestDto;
use App\Exception\CreateOrderDtoValidationException;

interface CreateOrderRequestDtoValidationServiceInterface
{
    /**
     * Validates the CreateOrderRequestDto and throws an exception if something is not OK.
     * @throws CreateOrderDtoValidationException
     */
    public function validateCreateOrderDtoData(CreateOrderRequestDto $createOrderRequestDto): bool;
}