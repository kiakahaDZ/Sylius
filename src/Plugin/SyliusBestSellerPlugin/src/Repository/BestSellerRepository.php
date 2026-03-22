<?php

declare(strict_types = 1)
;

namespace SyliusBestSellerPlugin\Repository;

use Doctrine\ORM\EntityRepository;
use SyliusBestSellerPlugin\Entity\BestSellerCache;

class BestSellerRepository extends EntityRepository
{
    public function findBestSellers(string $period, int $limit = 10): array
    {
        return $this->createQueryBuilder('b')
            ->where('b.period = :period')
            ->setParameter('period', $period)
            ->orderBy('b.position', 'ASC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }

    public function findOneByProductAndPeriod(int $productId, string $period): ?BestSellerCache
    {
        return $this->createQueryBuilder('b')
            ->where('b.productId = :productId')
            ->andWhere('b.period = :period')
            ->setParameter('productId', $productId)
            ->setParameter('period', $period)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function clearCache(string $period, ?\DateTimeInterface $startDate = null): void
    {
        $qb = $this->createQueryBuilder('b')
            ->delete()
            ->where('b.period = :period')
            ->setParameter('period', $period);

        if ($startDate) {
            $qb->andWhere('b.updatedAt >= :startDate')
                ->setParameter('startDate', $startDate);
        }

        $qb->getQuery()->execute();
    }

    public function findProductById(int $productId)
    {
        return $this->getEntityManager()
            ->createQueryBuilder()
            ->select('p')
            ->from('Sylius\Component\Core\Model\Product', 'p')
            ->where('p.id = :productId')
            ->setParameter('productId', $productId)
            ->getQuery()
            ->getOneOrNullResult();
    }
}