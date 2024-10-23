<?php

namespace App\Command;

use App\Repository\ProductRepository;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'products:is-visible',
    description: 'Update visible products.',
    aliases: ['app:is-visible-products']
    )]
class ProductsIsVisible extends Command {

    public function __construct(
        private ProductRepository $product,
    )
    {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output):int
    {
        $text = 'Обновлена информация о доступных товарах';

        $this->product->updateIsVisible(0);

        $output->writeln($text.'!');

        return Command::SUCCESS;
    }


}