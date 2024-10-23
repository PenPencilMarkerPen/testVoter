<?php

namespace App\Doctrine\Orm\Extension;

use ApiPlatform\Doctrine\Orm\Extension\QueryCollectionExtensionInterface;
use ApiPlatform\Doctrine\Orm\Extension\QueryItemExtensionInterface;
use Doctrine\ORM\QueryBuilder;
use ApiPlatform\Doctrine\Orm\Util\QueryNameGeneratorInterface;
use ApiPlatform\Metadata\Operation;
use Symfony\Bundle\SecurityBundle\Security;
use App\Entity\Product;

final readonly class VisibleProductExtension implements QueryCollectionExtensionInterface {

    public function __construct(
        private Security $security,
    )
    {}

    public function applyToCollection(QueryBuilder $queryBuilder, QueryNameGeneratorInterface $queryNameGenerator, string $resourceClass, ?Operation $operation = null, array $context = []): void
    {
        $this->addWhere($queryBuilder, $resourceClass);
    }

    private function AddWhere(QueryBuilder $queryBuilder, string $resourceClass):void {

        if (Product::class !== $resourceClass)
            return;
        
        $rootAlias = $queryBuilder->getRootAliases()[0];

        $queryBuilder->andWhere(sprintf('%s.isVisible = :visible', $rootAlias));
        $queryBuilder->setParameter('visible', true);
    }
}