<?php

declare(strict_types=1);

namespace SyliusRepairServicePlugin\Repository;

use Doctrine\ORM\EntityRepository;
use SyliusRepairServicePlugin\Entity\RepairRequest;

class RepairRequestRepository extends EntityRepository
{
    /** @return array<RepairRequest> */
    public function findLatest(int $limit = 20): array
    {
        return $this->createQueryBuilder('r')
            ->orderBy('r.createdAt', 'DESC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult()
        ;
    }

    /** @return array<RepairRequest> */
    public function findByCustomerEmail(string $customerEmail): array
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.customerEmail = :customerEmail')
            ->setParameter('customerEmail', $customerEmail)
            ->orderBy('r.createdAt', 'DESC')
            ->getQuery()
            ->getResult()
        ;
    }

    public function findOneByCode(string $code): ?RepairRequest
    {
        /** @var RepairRequest|null $repairRequest */
        $repairRequest = $this->findOneBy(['code' => $code]);

        return $repairRequest;
    }
}
