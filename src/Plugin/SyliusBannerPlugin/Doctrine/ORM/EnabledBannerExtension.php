<?php

declare(strict_types=1);

namespace Plugin\SyliusBannerPlugin\Doctrine\ORM;

use ApiPlatform\Doctrine\Orm\Extension\QueryCollectionExtensionInterface;
use ApiPlatform\Doctrine\Orm\Util\QueryNameGeneratorInterface;
use ApiPlatform\Metadata\Operation;
use Doctrine\ORM\QueryBuilder;
use Plugin\SyliusBannerPlugin\Entity\Banner;

final class EnabledBannerExtension implements QueryCollectionExtensionInterface
{
    public function applyToCollection(
        QueryBuilder $queryBuilder,
        QueryNameGeneratorInterface $queryNameGenerator,
        string $resourceClass,
        ?Operation $operation = null,
        array $context = [],
    ): void {
        if ($resourceClass !== Banner::class) {
            return;
        }

        $rootAlias = $queryBuilder->getRootAliases()[0];
        $queryBuilder->andWhere(sprintf('%s.enabled = :enabled', $rootAlias));
        $queryBuilder->setParameter('enabled', true);
        $queryBuilder->addOrderBy(sprintf('%s.sortOrder', $rootAlias), 'ASC');
        $queryBuilder->addOrderBy(sprintf('%s.id', $rootAlias), 'ASC');
    }
}
