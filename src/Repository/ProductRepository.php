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

    public function updateIsVisible(int $count)
    {

        $qb=$this->createQueryBuilder('p')
            ->update()
            ->set('p.isVisible', 'false')
            ->where('p.count = :count')
            ->setParameter('count', $count)
            ->getQuery()
            ->execute();

        return  $qb;
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

        return $db;
    }

    //    /**
    //     * @return Product[] Returns an array of Product objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('p')
    //            ->andWhere('p.count = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('p.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Product
    //    {
    //        return $this->createQueryBuilder('p')
    //            ->andWhere('p.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
