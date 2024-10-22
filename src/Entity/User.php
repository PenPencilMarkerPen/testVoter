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
    normalizationContext: ['groups' => ['user:read']],
    denormalizationContext: ['groups' => ['user:write']],
)]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    public int $id;

    #[ORM\Column(length: 180)]
    #[Groups(['user:read', 'user:write'])]
    public string $email;

    #[ORM\Column]
    #[Groups(['user:read'])]
    private array $roles=[];

    #[ORM\Column]
    #[Groups(['user:read', 'user:write'])]
    private string $password;

    #[ORM\OneToMany(targetEntity: Token::class, mappedBy: 'users')]
    private Collection $tokens;

    #[ORM\OneToMany(targetEntity: Brand::class, mappedBy: 'users')]
    #[Groups(['user:read'])]

    private Collection $brands;

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
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }


    public function getTokens(): Collection
    {
        return $this->tokens;
    }

    public function addToken(Token $token): static
    {
        if (!$this->tokens->contains($token)) {
            $this->tokens->add($token);
            $token->setUsers($this);
        }

        return $this;
    }

    public function removeToken(Token $token): static
    {
        if ($this->tokens->removeElement($token)) {
            if ($token->getUsers() === $this) {
                $token->setUsers(null);
            }
        }

        return $this;
    }

    public function getBrands(): Collection
    {
        return $this->brands;
    }

    public function addBrand(Brand $brand): static
    {
        if (!$this->brands->contains($brand)) {
            $this->brands->add($brand);
            $brand->setUsers($this);
        }

        return $this;
    }

    public function removeBrand(Brand $brand): static
    {
        if ($this->brands->removeElement($brand)) {
            if ($brand->getUsers() === $this) {
                $brand->setUsers(null);
            }
        }
        return $this;
    }
}
