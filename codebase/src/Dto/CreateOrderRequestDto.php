<?php

namespace App\Dto;

class CreateOrderRequestDto
{
    /**
     * @param int[] $products
     * @param string[] $promotionalCodes
     */
    public function __construct(private array $products, private array $promotionalCodes)
    {
    }

    /**
     * @return int[]
     */
    public function getProducts(): array
    {
        return $this->products;
    }

    /**
     * @return string[]
     */
    public function getPromotionalCodes(): array
    {
        return $this->promotionalCodes;
    }
}
