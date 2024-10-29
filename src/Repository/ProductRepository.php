<?php

namespace App\Repository;

use ApiPlatform\Doctrine\Orm\Paginator;
use App\Entity\Product;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\Tools\Pagination\Paginator as DoctrinePaginator;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class ProductRepository extends ServiceEntityRepository
{
    const ITEMS_PER_PAGE = 20;

    public function __construct(
        private ManagerRegistry $registry, 
        private TokenStorageInterface $token,
    ){
        parent::__construct($registry, Product::class);
    }


    public function updateIsVisible(int $count=0)
    {

        $qb=$this->createQueryBuilder('p')
            ->update()
            ->set('p.isVisible', 'false')
            ->where('p.count = :count')
            ->setParameter('count', $count)
            ->getQuery()
            ->execute();

    } 

    public function updateViews(int $id)
    {
        $db = $this->createQueryBuilder('p')
            ->update()
            ->set('p.view', 'p.view + 1')
            ->where('p.id = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->execute();

    }

    public function getProducts(int $offset, int $itemsPage): array {

        $queryBuilder = $this->createQueryBuilder('p');
        $queryBuilder->select()->where('p.isVisible != false');

        $query = $queryBuilder->getQuery()
            ->setFirstResult($offset)
            ->setMaxResults($itemsPage);

        return $query->getResult();   
    }

    public function getCountProducts(): int {
        $queryBuilder= $this->createQueryBuilder('p')
            ->select()->where('p.isVisible != false')->getQuery();
        
        return count($queryBuilder->getResult());
    }

}
