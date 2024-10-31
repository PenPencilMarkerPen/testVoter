<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Story\UserStory;
use App\Story\BrandStory;
use App\Story\FileStory;
use App\Story\ProductStory;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        UserStory::load();
        BrandStory::load();
        ProductStory::load();
        // $manager->flush();
    }
}
