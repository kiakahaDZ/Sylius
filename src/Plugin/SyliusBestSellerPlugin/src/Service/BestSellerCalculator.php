<?php

declare(strict_types=1);

namespace SyliusBestSellerPlugin\Service;

use Doctrine\ORM\EntityManagerInterface;
use Sylius\Component\Core\Model\OrderInterface;
use Sylius\Component\Core\Model\OrderItemInterface;
use Sylius\Component\Core\OrderPaymentStates;
use Sylius\Component\Payment\Model\PaymentInterface;
use SyliusBestSellerPlugin\Entity\BestSellerCache;
use SyliusBestSellerPlugin\Repository\BestSellerRepository;

class BestSellerCalculator
{
    private EntityManagerInterface $entityManager;
    private BestSellerRepository $bestSellerRepository;
    private array $periods;

    public function __construct(
        EntityManagerInterface $entityManager,
        BestSellerRepository $bestSellerRepository
    ) {
        $this->entityManager = $entityManager;
        $this->bestSellerRepository = $bestSellerRepository;
        $this->periods = ['daily', 'weekly', 'monthly', 'yearly', 'all_time'];
    }

    public function calculateForOrder(OrderInterface $order): void
    {
        if ($order->getPaymentState() !== OrderPaymentStates::STATE_PAID) {
            return;
        }

        foreach ($this->periods as $period) {
            foreach ($order->getItems() as $item) {
                $this->updateBestSellerCache($item, $period);
            }
        }
    }

    public function calculateForPeriod(string $period, ?\DateTimeInterface $startDate = null): void
    {
        $qb = $this->entityManager->createQueryBuilder();
        
        $qb->select('IDENTITY(oi.product) as product_id')
           ->addSelect('COUNT(DISTINCT o.id) as total_sales')
           ->addSelect('SUM(oi.quantity) as total_quantity')
           ->addSelect('SUM(oi.total) as total_revenue')
           ->from('Sylius\Component\Core\Model\OrderItem', 'oi')
           ->join('oi.order', 'o')
           ->where('o.paymentState = :state')
           ->setParameter('state', OrderPaymentStates::STATE_PAID);

        if ($startDate) {
            $qb->andWhere('o.completedAt >= :startDate')
               ->setParameter('startDate', $startDate);
        }

        $qb->groupBy('oi.product')
           ->orderBy('total_sales', 'DESC');

        $results = $qb->getQuery()->getResult();

        // Clear existing cache for this period
        $this->bestSellerRepository->clearCache($period, $startDate);
        
        // Update cache
        $position = 0;
        foreach ($results as $result) {
            $cache = new BestSellerCache();
            $cache->setProductId((int)$result['product_id']);
            $cache->setPeriod($period);
            $cache->setTotalSales((int)$result['total_sales']);
            $cache->setTotalQuantity((int)$result['total_quantity']);
            $cache->setTotalRevenue((float)$result['total_revenue']);
            $cache->setPosition(++$position);
            $cache->setUpdatedAt(new \DateTime());
            
            $this->entityManager->persist($cache);
        }
        
        $this->entityManager->flush();
    }

    public function calculateAll(): void
    {
        foreach ($this->periods as $period) {
            $startDate = $this->getStartDateForPeriod($period);
            $this->calculateForPeriod($period, $startDate);
        }
    }

    private function updateBestSellerCache(OrderItemInterface $item, string $period): void
    {
        $productId = $item->getProduct()?->getId();
        if (!$productId) {
            return;
        }

        $cache = $this->bestSellerRepository->findOneByProductAndPeriod($productId, $period);
        
        if (!$cache) {
            $cache = new BestSellerCache();
            $cache->setProductId($productId);
            $cache->setPeriod($period);
        }
        
        $cache->incrementTotalSales();
        $cache->incrementTotalQuantity($item->getQuantity());
        $cache->addRevenue((float)$item->getTotal());
        $cache->setUpdatedAt(new \DateTime());
        
        $this->entityManager->persist($cache);
        $this->entityManager->flush();
    }

    private function getStartDateForPeriod(string $period): ?\DateTimeInterface
    {
        $now = new \DateTime();
        
        switch ($period) {
            case 'daily':
                return (new \DateTime())->setTime(0, 0, 0);
            case 'weekly':
                return (new \DateTime())->modify('monday this week')->setTime(0, 0, 0);
            case 'monthly':
                return (new \DateTime())->modify('first day of this month')->setTime(0, 0, 0);
            case 'yearly':
                return (new \DateTime())->modify('first day of january')->setTime(0, 0, 0);
            case 'all_time':
                return null;
            default:
                return null;
        }
    }
}