<?php

namespace App\Factory;

use App\Entity\User;
use Zenstruck\Foundry\Persistence\PersistentProxyObjectFactory;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;


final class UserFactory extends PersistentProxyObjectFactory {
    
    public function __construct(
        private UserPasswordHasherInterface $userPasswordHasherInterface
    )
    {}

    public static function class():string {
        return User::class;
    }

    protected function defaults(): array {
        return [
            'email' => self::faker()->unique()->email(),
            'password' => $this->passwordHash(self::faker()->password()),
            'date' => \DateTimeImmutable::createFromMutable(self::faker()->dateTime()),
        ];
    }

    private function passwordHash($password)
    {
        return $this->userPasswordHasherInterface->hashPassword(
            new User(),
            $password,
        );
    }
}