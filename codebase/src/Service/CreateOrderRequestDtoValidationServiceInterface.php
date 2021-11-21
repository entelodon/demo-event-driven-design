<?php

namespace App\Service;

use App\Dto\CreateOrderRequestDto;
use App\Exception\CreateOrderDtoValidationException;
use App\Exception\NoProductsSuppliedException;

interface CreateOrderRequestDtoValidationServiceInterface
{
    /**
     * Validates the CreateOrderRequestDto and throws an exception if something is not OK.
     * @throws CreateOrderDtoValidationException | NoProductsSuppliedException
     */
    public function validateCreateOrderDtoData(CreateOrderRequestDto $createOrderRequestDto): bool;
}
