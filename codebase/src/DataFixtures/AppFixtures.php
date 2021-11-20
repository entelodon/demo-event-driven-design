<?php

namespace App\DataFixtures;

use App\DataFixtures\Constant\ProductTypeConstants;
use App\DataFixtures\Constant\PromotionalCodeConstants;
use App\Entity\Product;
use App\Entity\ProductType;
use App\Entity\PromotionalCode;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Persistence\ObjectRepository;

class AppFixtures extends Fixture
{
    private ObjectRepository $productTypeRepository;

    public function load(ObjectManager $manager): void
    {
        $this->productTypeRepository = $manager->getRepository(ProductType::class);
        $this->createAllProductTypes($manager);
        $this->createAllPromotionalCodes($manager);
        $this->createAllProducts($manager);
    }

    private function createAllProductTypes(ObjectManager $manager): void
    {
        $manager->persist($this->createProductType(ProductTypeConstants::TYPE_ACCESSORIES));
        $manager->persist($this->createProductType(ProductTypeConstants::TYPE_LAPTOP));
        $manager->persist($this->createProductType(ProductTypeConstants::TYPE_PC));
        $manager->persist($this->createProductType(ProductTypeConstants::TYPE_TV));
        $manager->flush();
    }

    private function createProductType(string $name): ProductType
    {
        $type = new ProductType();
        $type->setName($name);
        return $type;
    }

    private function createAllPromotionalCodes(ObjectManager $manager): void
    {
        $manager->persist($this->createPromotionalCode(
            PromotionalCodeConstants::DEAL_20_AMOUNT,
            PromotionalCodeConstants::DEAL_20_CODE,
            PromotionalCodeConstants::DEAL_20_EXACT,
            [
                ProductTypeConstants::TYPE_ACCESSORIES,
            ]
        ));
        $manager->persist($this->createPromotionalCode(
            PromotionalCodeConstants::DEAL_30_AMOUNT,
            PromotionalCodeConstants::DEAL_30_CODE,
            PromotionalCodeConstants::DEAL_30_EXACT,
            [
                ProductTypeConstants::TYPE_ACCESSORIES,
                ProductTypeConstants::TYPE_LAPTOP,
            ]
        ));
        $manager->persist($this->createPromotionalCode(
            PromotionalCodeConstants::FREE_50_AMOUNT,
            PromotionalCodeConstants::FREE_50_CODE,
            PromotionalCodeConstants::FREE_50_EXACT,
            [
                ProductTypeConstants::TYPE_LAPTOP,
                ProductTypeConstants::TYPE_PC,
            ]
        ));
        $manager->persist($this->createPromotionalCode(
            PromotionalCodeConstants::FREE_100_AMOUNT,
            PromotionalCodeConstants::FREE_100_CODE,
            PromotionalCodeConstants::FREE_100_EXACT,
            [
                ProductTypeConstants::TYPE_LAPTOP,
                ProductTypeConstants::TYPE_PC,
            ]
        ));
        $manager->flush();
    }

    /**
     * @param int $amount
     * @param string $code
     * @param bool $exactAmount
     * @param string[] $productTypes
     * @return PromotionalCode
     */
    private function createPromotionalCode(int $amount, string $code, bool $exactAmount, array $productTypes): PromotionalCode
    {
        $promotionalCode = new PromotionalCode();
        $promotionalCode->setAmount($amount);
        $promotionalCode->setCode($code);
        $promotionalCode->setExactAmount($exactAmount);
        foreach ($productTypes as $productType) {
            $productTypeEntity = $this->productTypeRepository->findOneBy([
                ProductTypeConstants::NAME => $productType,
            ]);
            $promotionalCode->addType($productTypeEntity);
        }
        return $promotionalCode;
    }

    private function createAllProducts(ObjectManager $manager): void
    {
        $manager->persist($this->createProduct(100000, 'Samsung 42" QLED TV', ProductTypeConstants::TYPE_TV));
        $manager->persist($this->createProduct(90000, 'LG 42" QLED TV', ProductTypeConstants::TYPE_TV));
        $manager->persist($this->createProduct(300000, 'Macbook PRO M1 PRO 16G', ProductTypeConstants::TYPE_LAPTOP));
        $manager->persist($this->createProduct(350000, 'MAC PRO', ProductTypeConstants::TYPE_PC));
        $manager->persist($this->createProduct(12500, 'HyperX Headset', ProductTypeConstants::TYPE_ACCESSORIES));
        $manager->flush();
    }

    private function createProduct(int $price, string $name, string $productType): Product
    {
        $product = new Product();
        $product->setPrice($price);
        $product->setName($name);
        $productTypeEntity = $this->productTypeRepository->findOneBy([
            ProductTypeConstants::NAME => $productType,
        ]);
        $product->setType($productTypeEntity);
        return $product;
    }
}
