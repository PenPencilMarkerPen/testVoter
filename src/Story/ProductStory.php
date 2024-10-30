<?php

namespace App\Story;

use App\Factory\ProductFactory;
use Zenstruck\Foundry\Story;

class ProductStory extends Story {

    public function build(): void
    {
        ProductFactory::createMany(200);
    }
}