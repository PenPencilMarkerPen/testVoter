<?php

namespace App\Entity;

use App\Repository\TokenRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TokenRepository::class)]
class Token extends BaseEntity
{

    #[ORM\Column(length: 255)]
    public string $token;

    #[ORM\ManyToOne(inversedBy: 'tokens')]
    public ?User $users;

}
