<?php

namespace App\Entity;

use App\Repository\ProductRepository;
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
use ApiPlatform\Metadata\ApiFilter;
use App\Filter\CategoryFilter;
use Doctrine\Common\Collections\ArrayCollection;
use App\State\ProductProvider;

#[ApiResource(
    normalizationContext: ['groups' => [self::PRODUCT_READ]],
    paginationItemsPerPage: 10,
)]  
#[Get()]
#[GetCollection(provider: ProductProvider::class)]
#[Delete(security: "is_granted('PRODUCT_DELETE', object)")]
#[Post(securityPostDenormalize: "is_granted('PRODUCT_CREATE', object)", 
denormalizationContext: ['groups' => [self::PRODUCT_WRITE]])]
#[Put(
    securityPostDenormalize: "is_granted('PRODUCT_EDIT', object)", 
)]
#[ORM\Entity(repositoryClass: ProductRepository::class)]
#[ApiResource(
    uriTemplate: '/brand/{brandId}/products',
    uriVariables: [
        'brandId' => new Link(fromClass: Brand::class, fromProperty: 'products'),
    ],
    operations: [
        new GetCollection(),
    ],
)]
#[ApiFilter(CategoryFilter::class)]
class Product extends BaseEntity
{
    public const PRODUCT_READ='product:read';
    public const PRODUCT_WRITE='product:write';
    public const PRODUCT_READ_OWNER ='owner:read';
    public const PRODUCT_WRITE_OWNER ='owner:write';
    public const PRODUCT_PUT_WRITE='product_put:write';

    #[ORM\Column(length: 65)]
    #[Groups([self::PRODUCT_READ, self::PRODUCT_WRITE, self::PRODUCT_PUT_WRITE])]
    public string $name;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups([self::PRODUCT_READ, self::PRODUCT_WRITE, self::PRODUCT_PUT_WRITE])]
    public ?string $description = null;

    #[ORM\Column(nullable: false)]
    #[Groups([self::PRODUCT_READ, self::PRODUCT_WRITE, self::PRODUCT_WRITE_OWNER])]
    public int $count = 0;

    #[ORM\Column(nullable: false)]
    #[Groups([self::PRODUCT_WRITE, self::PRODUCT_READ, self::PRODUCT_PUT_WRITE])]
    public int $view = 0;

    #[ORM\Column(nullable: false)]
    #[Groups([self::PRODUCT_WRITE, self::PRODUCT_READ_OWNER, self::PRODUCT_WRITE_OWNER])]
    public bool $isVisible = false;

    #[ORM\ManyToOne(inversedBy: 'products')]
    #[Groups([self::PRODUCT_READ, self::PRODUCT_WRITE, self::PRODUCT_PUT_WRITE])]
    public ?Brand $brand = null;

    #[ORM\OneToMany(targetEntity: File::class, mappedBy: 'product')]
    #[Groups([self::PRODUCT_READ])]
    public Collection $files;

    #[ORM\ManyToMany(targetEntity: Category::class, inversedBy: 'products')]
    #[Groups([self::PRODUCT_READ, self::PRODUCT_WRITE,  self::PRODUCT_WRITE_OWNER, self::PRODUCT_PUT_WRITE])]
    public $categories;

}
