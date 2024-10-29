<?php

namespace App\State;

use ApiPlatform\Metadata\CollectionOperationInterface;
use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\Pagination\Pagination;
use ApiPlatform\State\ProviderInterface;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Product;
use ApiPlatform\State\Pagination\TraversablePaginator;



  
class ProductProvider implements ProviderInterface
{

    public function __construct(
        #[Autowire(service: 'api_platform.doctrine.orm.state.item_provider')]
        private ProviderInterface $itemProvider,
        private EntityManagerInterface $entityManagerInterface,
        private ProductRepository $productRepository,
        private Pagination $pagination,
    )
    {}


    public function provide(Operation $operation, array $uriVariables = [], array $context = []): object|array|null
    {
        if ($operation instanceof CollectionOperationInterface){

            $currentPage = $this->pagination->getPage($context);
            $itemsPage = $this->pagination->getLimit($operation, $context);
            $offset = $this->pagination->getOffset($operation, $context);
            $totalItems = $this->countTotalProducts();

            $products= $this->createProducts($offset, $itemsPage);

            return new TraversablePaginator(
                new \ArrayIterator($products),
                $currentPage,
                $itemsPage, 
                $totalItems,
            );
        }

    }

    private function countTotalProducts():int {
        return $this->productRepository->getCountProducts();
    }

    private function createProducts(int $offset, int $itemsPage):array
    {
        return $this->productRepository->getProducts($offset, $itemsPage);
    }
}
