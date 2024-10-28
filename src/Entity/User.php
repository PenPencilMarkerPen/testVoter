<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\GetCollection;
use App\Repository\UserRepository;
use App\State\UserProcessor;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: '`user`')]
#[ORM\UniqueConstraint(name: 'UNIQ_IDENTIFIER_EMAIL', fields: ['email'])]
#[ApiResource(
    operations: [
        new Post(
            processor: UserProcessor::class,
            uriTemplate: '/register',
        ),
        new GetCollection()
    ],
    normalizationContext: ['groups' => [self::USER_READ]],
    denormalizationContext: ['groups' => [self::USER_WRITE]],
)]
class User extends BaseEntity implements UserInterface, PasswordAuthenticatedUserInterface
{
    public const USER_READ='user:read';
    public const USER_WRITE='user:write';

    #[ORM\Column(length: 180)]
    #[Groups([self::USER_READ, self::USER_WRITE])]
    public string $email;

    #[ORM\Column]
    #[Groups([self::USER_READ])]
    private array $roles=[];

    #[ORM\Column]
    #[Groups([self::USER_READ, self::USER_WRITE])]
    private string $password;

    #[ORM\OneToMany(targetEntity: Token::class, mappedBy: 'users')]
    public Collection $tokens;

    #[ORM\OneToMany(targetEntity: Brand::class, mappedBy: 'users')]
    #[Groups([self::USER_READ])]
    public Collection $brands;

    public function __construct()
    {
        $this->tokens = new ArrayCollection();
        $this->brands = new ArrayCollection();
    }

    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    public function getRoles(): array
    {
        $roles = $this->roles;
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    public function eraseCredentials(): void
    {}

}
