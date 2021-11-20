<?php

namespace App\Service;

use App\Dto\CreateOrderRequestDto;
use App\Exception\ProductDoesNotExistException;
use App\Exception\PromotionalCodeDoesNotExistException;
use App\Repository\ProductRepository;
use App\Repository\PromotionalCodeRepository;

class CreateOrderRequestDtoValidationService implements CreateOrderRequestDtoValidationServiceInterface
{
    public function __construct(private PromotionalCodeRepository $promotionalCodeRepository, private ProductRepository $productRepository)
    {
    }

    /**
     * {@inheritdoc}
     */
    public function validateCreateOrderDtoData(CreateOrderRequestDto $createOrderRequestDto): bool
    {
        foreach ($createOrderRequestDto->getProducts() as $productId) {
            if ($this->productRepository->find($productId) === null) {
                throw new ProductDoesNotExistException($productId);
            }
        }

        foreach ($createOrderRequestDto->getPromotionalCodes() as $promotionalCode) {
            if ($this->promotionalCodeRepository->findOneByCode($promotionalCode) === null) {
                throw new PromotionalCodeDoesNotExistException($promotionalCode);
            }
        }

        return true;
    }

}
