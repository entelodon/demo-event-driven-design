<?php

namespace App\Service;

use App\Entity\Product;

interface ProductServiceInterface
{
    /**
     * Finds a product by given id, or returns null.
     */
    public function findProductById(int $id): ?Product;
}
