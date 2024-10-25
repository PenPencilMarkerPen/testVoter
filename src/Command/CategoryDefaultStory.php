<?php

namespace App\Command;


use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use App\Story\CategoryStory;

#[AsCommand(
    name: 'category:story',
    description: 'Insert category.',
    aliases: ['app:category-story']
    )]
class CategoryDefaultStory extends Command {

    public function execute(InputInterface $input, OutputInterface $output):int
    {

        $text = 'Добавлены категории!';

        CategoryStory::load();

        $output->writeln($text.'!');

        return Command::SUCCESS;
    }

}
