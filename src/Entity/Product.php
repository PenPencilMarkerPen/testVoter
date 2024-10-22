<?php

namespace App\Entity;

use App\Repository\ProductRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
use Symfony\Component\Serializer\Annotation\Groups;
use ApiPlatform\Metadata\Link;
use App\State\ProductProvider;

#[ApiResource(
    normalizationContext: ['groups' => ['product:read']],
    denormalizationContext: ['groups' => ['product:write']],
)]
#[GetCollection]
// #[Get(security: "is_granted('PRODUCT_READ', object)")]
#[Get(provider: ProductProvider::class)]
#[Delete(security: "is_granted('PRODUCT_DELETE', object)")]
#[Post(securityPostDenormalize: "is_granted('PRODUCT_CREATE', object)")]
#[Put(securityPostDenormalize: "is_granted('PRODUCT_EDIT', object)")]
#[ORM\Entity(repositoryClass: ProductRepository::class)]
#[ApiResource(
    uriTemplate: '/brand/{brandId}/products',
    uriVariables: [
        'brandId' => new Link(fromClass: Brand::class, fromProperty: 'products'),
    ],
    operations: [
        new GetCollection()
    ],
)]
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

    #[ORM\OneToMany(targetEntity: File::class, mappedBy: 'product')]
    #[Groups(['product:read'])]
    private Collection $files;

    public function __construct()
    {
        $this->files = new ArrayCollection();
    }


    public function getBrand(): ?Brand
    {
        return $this->brand;
    }

    public function setBrand(?Brand $brand): static
    {
        $this->brand = $brand;

        return $this;
    }

    public function getFiles(): Collection
    {
        return $this->files;
    }

    public function addFile(File $file): static
    {
        if (!$this->files->contains($file)) {
            $this->files->add($file);
            $file->setProduct($this);
        }

        return $this;
    }

    public function removeFile(File $file): static
    {
        if ($this->files->removeElement($file)) {
            if ($file->getProduct() === $this) {
                $file->setProduct(null);
            }
        }

        return $this;
    }
}
