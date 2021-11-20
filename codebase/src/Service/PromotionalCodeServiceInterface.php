<?php

namespace App\Service;

use App\Entity\PromotionalCode;

interface PromotionalCodeServiceInterface
{
    /**
     * Finds a Promotional Code by given code, or returns null.
     */
    public function findPromotionalCodeByCode(string $code): ?PromotionalCode;
}
