<?php

namespace App\Dto;

use JetBrains\PhpStorm\ArrayShape;

class CreatedOrderResponseDto implements \JsonSerializable
{
    const PRODUCT_NAMES = 'productNames';
    const PROMOTIONAL_CODES = 'promotionalCodes';
    const PRICE = 'price';
    const DISCOUNT_PRICE = 'discountPrice';
    const FINAL_PRICE = 'finalPrice';

    /**
     * @param string[] $productNames
     * @param string[] $promotionalCodes
     * @param int $price
     * @param int $discountPrice
     * @param int $finalPrice
     */
    public function __construct(private array $productNames, private array $promotionalCodes, private int $price, private int $discountPrice, private int $finalPrice,)
    {
    }

    #[ArrayShape([self::PRODUCT_NAMES => "string[]", self::PROMOTIONAL_CODES => "string[]", self::PRICE => "int", self::DISCOUNT_PRICE => "int", self::FINAL_PRICE => "int"])]
    public function jsonSerialize(): array
    {
        return [
            self::PRODUCT_NAMES => $this->productNames,
            self::PROMOTIONAL_CODES => $this->promotionalCodes,
            self::PRICE => $this->price,
            self::DISCOUNT_PRICE => $this->discountPrice,
            self::FINAL_PRICE => $this->finalPrice,
        ];
    }
}
