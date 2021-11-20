<?php

namespace App\Service;

use App\Entity\Product;
use App\Repository\ProductRepository;

class ProductService implements ProductServiceInterface
{
    public function __construct(private ProductRepository $productRepository)
    {
    }

    /**
     * {@inheritdoc}
     */
    public function findProductById(int $id): ?Product
    {
        return $this->productRepository->find($id);
    }
}
