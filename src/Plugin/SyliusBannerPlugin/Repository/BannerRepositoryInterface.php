<?php

declare(strict_types=1);

namespace Plugin\SyliusBannerPlugin\Repository;

use Doctrine\ORM\QueryBuilder;
use Plugin\SyliusBannerPlugin\Entity\BannerInterface;
use Sylius\Resource\Doctrine\Persistence\RepositoryInterface;

interface BannerRepositoryInterface extends RepositoryInterface
{
    /**
     * @return BannerInterface[]
     */
    public function findByPosition(string $position): array;

    /**
     * @return BannerInterface[]
     */
    public function findEnabled(): array;

    public function createListQueryBuilder(): QueryBuilder;
}
