<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Metadata\Post;

#[ApiResource(
    normalizationContext: ['groups' => ['category:read']],
    denormalizationContext: ['groups' => ['category:write']],
)]
#[Post()]
#[ORM\Entity()]
class Category extends BaseEntity{

    public const CATEGORY_WRITE='category:read';
    public const CATEGORY_READ='category:write';

    #[ORM\Column(length: 65)]
    #[Groups([self::CATEGORY_READ, self::CATEGORY_WRITE])]
    public string $category;

    #[ORM\OneToMany(targetEntity: Product::class, mappedBy: 'category')]
    public Collection $products;
}