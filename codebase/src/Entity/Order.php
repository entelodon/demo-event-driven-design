<?php

namespace App\Entity;

use App\Repository\OrderRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=OrderRepository::class)
 * @ORM\Table(name="`order`")
 */
class Order
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToMany(targetEntity=Product::class)
     */
    private $products;

    /**
     * @ORM\ManyToMany(targetEntity=PromotionalCode::class)
     */
    private $promotionalCodes;

    /**
     * @ORM\Column(type="integer")
     */
    private $price;

    /**
     * @ORM\Column(type="integer")
     */
    private $discountPrice;

    /**
     * @ORM\Column(type="integer")
     */
    private $finalPrice;

    public function __construct()
    {
        $this->products = new ArrayCollection();
        $this->promotionalCodes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection|Product[]
     */
    public function getProducts(): Collection
    {
        return $this->products;
    }

    public function addProduct(Product $product): self
    {
        if (!$this->products->contains($product)) {
            $this->products[] = $product;
        }

        return $this;
    }

    public function removeProduct(Product $product): self
    {
        $this->products->removeElement($product);

        return $this;
    }

    /**
     * @return Collection|PromotionalCode[]
     */
    public function getPromotionalCodes(): Collection
    {
        return $this->promotionalCodes;
    }

    public function addPromotionalCode(PromotionalCode $promotionalCode): self
    {
        if (!$this->promotionalCodes->contains($promotionalCode)) {
            $this->promotionalCodes[] = $promotionalCode;
        }

        return $this;
    }

    public function removePromotionalCode(PromotionalCode $promotionalCode): self
    {
        $this->promotionalCodes->removeElement($promotionalCode);

        return $this;
    }

    public function getPrice(): ?int
    {
        return $this->price;
    }

    public function setPrice(int $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getDiscountPrice(): ?int
    {
        return $this->discountPrice;
    }

    public function setDiscountPrice(int $discountPrice): self
    {
        $this->discountPrice = $discountPrice;

        return $this;
    }

    public function getFinalPrice(): ?int
    {
        return $this->finalPrice;
    }

    public function setFinalPrice(int $finalPrice): self
    {
        $this->finalPrice = $finalPrice;

        return $this;
    }
}
