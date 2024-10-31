<?php

namespace App\Story;


use App\Factory\FileFactory;
use Zenstruck\Foundry\Story;

final class FileStory extends Story {
  
    public function build():void
    {
        FileFactory::createMany(150);
    }
}