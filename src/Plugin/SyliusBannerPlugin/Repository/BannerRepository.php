<?php

declare(strict_types=1);

namespace Plugin\SyliusBannerPlugin\Repository;

use Doctrine\ORM\QueryBuilder;
use Plugin\SyliusBannerPlugin\Entity\BannerInterface;
use Sylius\Bundle\ResourceBundle\Doctrine\ORM\EntityRepository;

class BannerRepository extends EntityRepository implements BannerRepositoryInterface
{
    /**
     * @return BannerInterface[]
     */
    public function findByPosition(string $position): array
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.position = :position')
            ->andWhere('b.enabled = :enabled')
            ->setParameter('position', $position)
            ->setParameter('enabled', true)
            ->orderBy('b.sortOrder', 'ASC')
            ->addOrderBy('b.id', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * @return BannerInterface[]
     */
    public function findEnabled(): array
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.enabled = :enabled')
            ->setParameter('enabled', true)
            ->orderBy('b.sortOrder', 'ASC')
            ->addOrderBy('b.position', 'ASC')
            ->addOrderBy('b.id', 'ASC')
            ->getQuery()
            ->getResult();
    }

    public function createListQueryBuilder(): QueryBuilder
    {
        return $this->createQueryBuilder('b')
            ->orderBy('b.sortOrder', 'ASC')
            ->addOrderBy('b.position', 'ASC')
            ->addOrderBy('b.id', 'ASC');
    }
}
