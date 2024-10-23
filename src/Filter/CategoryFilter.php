<?php

namespace App\Filter;

use ApiPlatform\Doctrine\Orm\Filter\AbstractFilter;
use ApiPlatform\Doctrine\Orm\Util\QueryNameGeneratorInterface;
use ApiPlatform\Metadata\Operation;
use Doctrine\ORM\QueryBuilder;

class CategoryFilter extends AbstractFilter {
    
    protected function filterProperty(string $property, $value, QueryBuilder $queryBuilder, QueryNameGeneratorInterface $queryNameGenerator, string $resourceClass, Operation $operation = null, array $context = []): void
    {
       $alias = $queryBuilder->getRootAliases()[0];
       if ($property == 'popular')
       {
            $this->selectPopularProducts($queryBuilder, $alias);
       }
       elseif($property == 'new')
       {
            $this->selectNewProducts($queryBuilder, $alias);
       }
       return;
    }

    // описание для swagger
    public function getDescription(string $resourceClass): array
    {
        return [];
    }

    private function selectPopularProducts(QueryBuilder $queryBuilder, $alias)
    {
        $queryBuilder->andWhere(sprintf('%s.view > 10', $alias))
        ->addOrderBy(sprintf('%s.view', $alias), 'DESC');
    }

    private function selectNewProducts(QueryBuilder $queryBuilder, $alias)
    {

        $date = new \DateTimeImmutable();
        $newDate = $date->sub(new \DateInterval('P3D'));

        $queryBuilder->andWhere(sprintf('%s.date >= :date', $alias))
            ->andWhere(sprintf('%s.view = 0', $alias))
            ->setParameter('date', $newDate->format('Y-m-d 00:00:00'));
    }
}