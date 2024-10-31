<?php

namespace App\Factory;

use App\Entity\Brand;
use Zenstruck\Foundry\Persistence\PersistentProxyObjectFactory;
use function Zenstruck\Foundry\lazy;


final class BrandFactory extends PersistentProxyObjectFactory {
    

    public static function class(): string {
        return Brand::class;
    }

    protected function defaults(): array {
        return [
            'name' => self::faker()->unique()->text(10),
            'users' => lazy(fn() => UserFactory::randomOrCreate()),
            'date' => \DateTimeImmutable::createFromMutable(self::faker()->dateTime()),
        ];
    }
}  