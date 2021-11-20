<?php

namespace App\Service;

use App\Entity\PromotionalCode;
use App\Repository\PromotionalCodeRepository;

class PromotionalCodeService implements PromotionalCodeServiceInterface
{
    public function __construct(private PromotionalCodeRepository $promotionalCodeRepository)
    {
    }

    /**
     * {@inheritdoc}
     */
    public function findPromotionalCodeByCode(string $code): ?PromotionalCode
    {
        return $this->promotionalCodeRepository->findOneByCode($code);
    }
}
