<?php

namespace App\Factory;

use App\Entity\File;
use Zenstruck\Foundry\Persistence\PersistentProxyObjectFactory;
use function Zenstruck\Foundry\lazy;

final class FileFactory extends PersistentProxyObjectFactory {

    public static function class(): string {
        return File::class;
    }

    protected function defaults():array {
        return [
            'mimeType' => self::faker()->mimeType(),
        ];
    }
}