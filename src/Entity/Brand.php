<?php

namespace App\Entity;

use App\Repository\BrandRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Link;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
use Symfony\Component\Serializer\Annotation\Groups;

#[ApiResource(
    normalizationContext: ['groups' => ['brand:read']],
    denormalizationContext: ['groups' => ['brand:write']],
)]
#[GetCollection]
#[Get(security: "is_granted('BRAND_READ', object)")]
// #[Get()]
// #[Put(security: "is_granted('BRAND_EDIT', object)")]
#[Delete(security: "is_granted('BRAND_DELETE', object)")]
#[Post(securityPostDenormalize: "is_granted('BRAND_CREATE', object)")]
#[Put(securityPostDenormalize: "is_granted('BRAND_EDIT', object)")]
#[ORM\Entity()]
class Brand extends BaseEntity
{  
    public const BRAND_READ='brand:read';
    public const BRAND_WRITE='brand:write';
    
    #[ORM\Column(length: 65)]
    #[Groups([self::BRAND_READ, self::BRAND_WRITE])]
    public string $name;

    #[ORM\ManyToOne(inversedBy: 'brands')]
    #[Groups([self::BRAND_READ, self::BRAND_WRITE])]
    private ?User $users = null;   

    #[ORM\OneToMany(targetEntity: Product::class, mappedBy: 'brand')]
    #[Groups([self::BRAND_READ])]
    private Collection $products;

    public function __construct()
    {
        $this->products = new ArrayCollection();
    }

    public function getUsers(): ?User
    {
        return $this->users;
    }

    public function setUsers(?User $users): static
    {
        $this->users = $users;

        return $this;
    }

    public function getProducts(): Collection
    {
        return $this->products;
    }

    public function addProduct(Product $product): static
    {
        if (!$this->products->contains($product)) {
            $this->products->add($product);
            $product->setBrand($this);
        }

        return $this;
    }

    public function removeProduct(Product $product): static
    {
        if ($this->products->removeElement($product)) {
            if ($product->getBrand() === $this) {
                $product->setBrand(null);
            }
        }

        return $this;
    }

}
