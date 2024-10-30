<?php

namespace App\Story;


use App\Factory\BrandFactory;
use Zenstruck\Foundry\Story;

final class BrandStory extends Story {

    public function build():void
    {
        BrandFactory::createMany(50);
    }
}