<?php

namespace App\Factory;

use App\Dto\CreateOrderRequestDto;
use App\Exception\InvalidRequestException;
use Symfony\Component\HttpFoundation\Request;

class CreateOrderRequestFactory
{
    const PRODUCTS = 'products';
    const PROMOTIONAL_CODES = 'promotionalCodes';

    /**
     * @throws InvalidRequestException
     */
    public function createFromRequest(Request $request): CreateOrderRequestDto
    {
        $requestArray = $request->toArray();
        if (!array_key_exists(self::PRODUCTS, $requestArray)) {
            throw new InvalidRequestException(self::PRODUCTS);
        }
        if (!array_key_exists(self::PROMOTIONAL_CODES, $requestArray)) {
            throw new InvalidRequestException(self::PROMOTIONAL_CODES);
        }
        return new CreateOrderRequestDto($requestArray[self::PRODUCTS], $requestArray[self::PROMOTIONAL_CODES]);
    }
}
