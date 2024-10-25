<?php

namespace App\Factory;

use App\Entity\Category;
use App\Repository\CategoryRepository;
use Zenstruck\Foundry\Persistence\PersistentProxyObjectFactory;

final class CategoryFactory extends PersistentProxyObjectFactory {

    public static function class():string {
        return Category::class;
    }


    protected function defaults(): array
    {
        return [
            'category' => self::faker()->text(50),
        ];
    }



}