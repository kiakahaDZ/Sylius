<?php

declare(strict_types = 1)
;

namespace SyliusOffersPlugin\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use SyliusOffersPlugin\Entity\Offer;

class OfferRepository extends EntityRepository
{
    public function createListQueryBuilder(): QueryBuilder
    {
        return $this->createQueryBuilder('o')
            ->orderBy('o.position', 'ASC')
            ->addOrderBy('o.createdAt', 'DESC');
    }

    public function findActiveOffers(string $type = null, ?string $url = null, ?string $locale = null): array
    {
        $qb = $this->createQueryBuilder('o')
            ->where('o.enabled = :enabled')
            ->andWhere('o.startDate IS NULL OR o.startDate <= :now')
            ->andWhere('o.endDate IS NULL OR o.endDate >= :now')
            ->setParameter('enabled', true)
            ->setParameter('now', new \DateTime())
            ->orderBy('o.position', 'ASC');

        if ($type) {
            $qb->andWhere('o.type = :type')
                ->setParameter('type', $type);
        }

        $offers = $qb->getQuery()->getResult();

        // Filter by targeting
        return array_filter($offers, function (Offer $offer) use ($url, $locale) {
            return $offer->isTargeted($url ?? '', $locale);
        });
    }

    public function findActiveByType(string $type, int $limit = 10): array
    {
        return $this->createQueryBuilder('o')
            ->where('o.enabled = :enabled')
            ->andWhere('o.type = :type')
            ->andWhere('o.startDate IS NULL OR o.startDate <= :now')
            ->andWhere('o.endDate IS NULL OR o.endDate >= :now')
            ->setParameter('enabled', true)
            ->setParameter('type', $type)
            ->setParameter('now', new \DateTime())
            ->orderBy('o.position', 'ASC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }

    public function findUpcomingOffers(int $days = 7): array
    {
        $startThreshold = new \DateTime("+{$days} days");

        return $this->createQueryBuilder('o')
            ->where('o.enabled = :enabled')
            ->andWhere('o.startDate IS NOT NULL')
            ->andWhere('o.startDate <= :threshold')
            ->andWhere('o.startDate > :now')
            ->setParameter('enabled', true)
            ->setParameter('threshold', $startThreshold)
            ->setParameter('now', new \DateTime())
            ->orderBy('o.startDate', 'ASC')
            ->getQuery()
            ->getResult();
    }

    public function findExpiringOffers(int $days = 7): array
    {
        $endThreshold = new \DateTime("+{$days} days");

        return $this->createQueryBuilder('o')
            ->where('o.enabled = :enabled')
            ->andWhere('o.endDate IS NOT NULL')
            ->andWhere('o.endDate <= :threshold')
            ->andWhere('o.endDate > :now')
            ->setParameter('enabled', true)
            ->setParameter('threshold', $endThreshold)
            ->setParameter('now', new \DateTime())
            ->orderBy('o.endDate', 'ASC')
            ->getQuery()
            ->getResult();
    }

    public function findBestPerformingOffers(int $limit = 10): array
    {
        return $this->createQueryBuilder('o')
            ->where('o.enabled = :enabled')
            ->setParameter('enabled', true)
            ->orderBy('o.conversionRate', 'DESC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }

    public function incrementViews(int $offerId): void
    {
        $this->createQueryBuilder('o')
            ->update()
            ->set('o.views', 'o.views + 1')
            ->where('o.id = :id')
            ->setParameter('id', $offerId)
            ->getQuery()
            ->execute();
    }

    public function incrementClicks(int $offerId): void
    {
        $this->createQueryBuilder('o')
            ->update()
            ->set('o.clicks', 'o.clicks + 1')
            ->set('o.conversionRate', '(o.clicks + 1) / NULLIF(o.views, 0) * 100')
            ->where('o.id = :id')
            ->setParameter('id', $offerId)
            ->getQuery()
            ->execute();
    }
}