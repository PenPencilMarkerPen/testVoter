<?php

namespace App\Entity;

use App\Repository\ProductRepository;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
use Symfony\Component\Serializer\Annotation\Groups;

#[ApiResource(
    normalizationContext: ['groups' => ['product:read']],
    denormalizationContext: ['groups' => ['product:write']],
)]
#[GetCollection]
// #[Get(security: "is_granted('PRODUCT_READ', object)")]
#[Get()]
#[Delete(security: "is_granted('PRODUCT_DELETE', object)")]
#[Post(securityPostDenormalize: "is_granted('PRODUCT_CREATE', object)")]
#[Put(securityPostDenormalize: "is_granted('PRODUCT_EDIT', object)")]
#[ORM\Entity(repositoryClass: ProductRepository::class)]
class Product
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    public ?int $id = null;

    #[ORM\Column(length: 65)]
    #[Groups(['product:read', 'product:write'])]
    public ?string $name = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['product:read', 'product:write'])]
    public ?string $description = null;

    #[ORM\Column(nullable: false)]
    #[Groups(['product:read', 'product:write'])]
    public int $count = 0;

    #[ORM\Column(nullable: false)]
    #[Groups(['product:read', 'product:write'])]
    public int $view = 0;

    #[ORM\Column(nullable: false)]
    #[Groups(['product:read', 'product:write'])]
    public bool $isVisible = false;

    #[ORM\ManyToOne(inversedBy: 'products')]
    #[Groups(['product:read', 'product:write'])]
    private ?Brand $brand = null;


    public function getBrand(): ?Brand
    {
        return $this->brand;
    }

    public function setBrand(?Brand $brand): static
    {
        $this->brand = $brand;

        return $this;
    }
}
