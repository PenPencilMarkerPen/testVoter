<?php

namespace App\Repository;

use App\Entity\Product;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;


class ProductRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
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

}
