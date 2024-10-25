<?php

namespace App\Story;

use Zenstruck\Foundry\Story;
use App\Factory\CategoryFactory;

final class CategoryStory extends Story {


    public function build():void 
    {
        $categories = ['Книги', 'Обувь', 'Учебники', 'Фрукты'];

        CategoryFactory::createSequence(
            function() use ($categories){
                foreach($categories as $category)
                {
                    yield ['category' => $category];
                }
            }
        );
    }

}
