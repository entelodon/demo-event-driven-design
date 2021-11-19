<?php

namespace App\Entity;

use App\Repository\PromotionalCodeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=PromotionalCodeRepository::class)
 */
class PromotionalCode
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     */
    private $code;

    /**
     * @ORM\Column(type="integer")
     */
    private $amount;

    /**
     * @ORM\Column(type="boolean")
     */
    private $exactAmount;

    /**
     * @ORM\ManyToMany(targetEntity=ProductType::class)
     */
    private $types;

    public function __construct()
    {
        $this->types = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(string $code): self
    {
        $this->code = $code;

        return $this;
    }

    public function getAmount(): ?int
    {
        return $this->amount;
    }

    public function setAmount(int $amount): self
    {
        $this->amount = $amount;

        return $this;
    }

    public function getExactAmount(): ?bool
    {
        return $this->exactAmount;
    }

    public function setExactAmount(bool $exactAmount): self
    {
        $this->exactAmount = $exactAmount;

        return $this;
    }

    /**
     * @return Collection|ProductType[]
     */
    public function getTypes(): Collection
    {
        return $this->types;
    }

    public function addType(ProductType $type): self
    {
        if (!$this->types->contains($type)) {
            $this->types[] = $type;
        }

        return $this;
    }

    public function removeType(ProductType $type): self
    {
        $this->types->removeElement($type);

        return $this;
    }
}
