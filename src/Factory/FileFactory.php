<?php

namespace App\Factory;

use App\Entity\File;
use Zenstruck\Foundry\Persistence\PersistentProxyObjectFactory;
use function Zenstruck\Foundry\lazy;
use App\Factory\ProductFactory;

final class FileFactory extends PersistentProxyObjectFactory {

    public static function class(): string {
        return File::class;
    }
 
    protected function defaults():array {
        return [
            'mimeType' => self::faker()->mimeType(),
            'filePath' => self::faker()->fileExtension(),
            'fileSize' => self::faker()->numberBetween(100, 1000),
            'product' => lazy(fn() => ProductFactory::randomOrCreate()),
        ];
    }
}  