<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Get;
use Symfony\Component\Serializer\Annotation\Groups;

#[ApiResource(
    operations: [
        new Post(),
        new GetCollection(),
        new Get(security: "is_granted('BOOK_READ', object)"),
    ],
    normalizationContext: ['groups' => ['book:read']],
    denormalizationContext: ['groups' => ['book:write']],
)]
#[ORM\Entity()]
class Book
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    public int $id;

    #[ORM\Column(length: 255)]
    #[Groups(['user:read','book:read', 'book:write'])]
    public ?string $name = null;

    #[ORM\Column(length: 255)]
    #[Groups(['user:read','book:read', 'book:write'])]
    public ?string $author = null;

    #[ORM\ManyToOne(inversedBy: 'books')]
    #[Groups(['book:read', 'book:write'])]
    private ?User $users = null;


    public function getUsers(): ?User
    {
        return $this->users;
    }

    public function setUsers(?User $users): static
    {
        $this->users = $users;

        return $this;
    }
}