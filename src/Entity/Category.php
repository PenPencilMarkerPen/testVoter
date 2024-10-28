<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Metadata\Post;
use App\Repository\CategoryRepository;

#[ApiResource(
    normalizationContext: ['groups' => [self::CATEGORY_READ]],
    denormalizationContext: ['groups' => [self::CATEGORY_WRITE]],
)]
#[Post()]
#[ORM\Entity(repositoryClass: CategoryRepository::class)]
class Category extends BaseEntity{

    public const CATEGORY_WRITE='category:read';
    public const CATEGORY_READ='category:write';

    #[ORM\Column(length: 65)]
    #[Groups([Product::PRODUCT_READ, self::CATEGORY_READ, self::CATEGORY_WRITE])]
    public string $category;

    #[ORM\ManyToMany(targetEntity: Product::class, mappedBy: 'categories')]
    public  $products;

}