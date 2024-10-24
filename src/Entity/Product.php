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
use ApiPlatform\Metadata\ApiFilter;
use App\Filter\CategoryFilter;

#[ApiResource(
    normalizationContext: ['groups' => ['product:read']],
    denormalizationContext: ['groups' => ['product:write']],
)]
#[GetCollection()]
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
#[ApiFilter(CategoryFilter::class)]
class Product extends BaseEntity
{
    public const PRODUCT_READ='product:read';
    public const PRODUCT_WRITE='product:write';


    #[ORM\Column(length: 65)]
    #[Groups([self::PRODUCT_READ, self::PRODUCT_WRITE])]
    public string $name;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups([self::PRODUCT_READ, self::PRODUCT_WRITE])]
    public ?string $description = null;

    #[ORM\Column(nullable: false)]
    #[Groups([self::PRODUCT_READ, self::PRODUCT_WRITE])]
    public int $count = 0;

    #[ORM\Column(nullable: false)]
    #[Groups([self::PRODUCT_READ, self::PRODUCT_WRITE])]
    public int $view = 0;

    #[ORM\Column(nullable: false)]
    #[Groups([self::PRODUCT_READ, self::PRODUCT_WRITE])]
    public bool $isVisible = false;

    #[ORM\ManyToOne(inversedBy: 'products')]
    #[Groups([self::PRODUCT_READ, self::PRODUCT_WRITE])]
    private ?Brand $brand = null;

    #[ORM\OneToMany(targetEntity: File::class, mappedBy: 'product')]
    #[Groups([self::PRODUCT_READ])]
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
