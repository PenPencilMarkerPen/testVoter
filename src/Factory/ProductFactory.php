<?php

namespace App\Factory;

use App\Entity\Product;
use Zenstruck\Foundry\Persistence\PersistentProxyObjectFactory;
use function Zenstruck\Foundry\lazy;

final class ProductFactory extends PersistentProxyObjectFactory {

    public static function class():string {
        return Product::class;
    }

    public function defaults(): array {
        return [
            'name' => self::faker()->text(10),
            'description' => self::faker()->text(255),
            'count' => self::faker()->numberBetween(0,1000),
            'view' => self::faker()->numberBetween(0,10000),
            'isVisible' => self::faker()->boolean(),
            'brand' => lazy(fn() => BrandFactory::randomOrCreate()),
        ];
    }
}
