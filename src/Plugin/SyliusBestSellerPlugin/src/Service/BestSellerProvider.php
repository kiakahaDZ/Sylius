<?php

declare(strict_types = 1)
;

namespace SyliusBestSellerPlugin\Service;

use Sylius\Component\Core\Model\ProductInterface;
use SyliusBestSellerPlugin\Repository\BestSellerRepository;

class BestSellerProvider
{
    private BestSellerRepository $bestSellerRepository;

    public function __construct(BestSellerRepository $bestSellerRepository)
    {
        $this->bestSellerRepository = $bestSellerRepository;
    }

    public function getBestSellers(string $period = 'weekly', int $limit = 10, ?int $channelId = null): array
    {
        $cacheEntries = $this->bestSellerRepository->findBestSellers($period, $limit);

        $bestSellers = [];
        foreach ($cacheEntries as $entry) {
            $product = $this->bestSellerRepository->findProductById($entry->getProductId());
            if ($product && $this->isProductAvailableForChannel($product, $channelId)) {
                $bestSellers[] = [
                    'product' => $product,
                    'total_sales' => $entry->getTotalSales(),
                    'total_quantity' => $entry->getTotalQuantity(),
                    'total_revenue' => $entry->getTotalRevenue(),
                    'position' => $entry->getPosition(),
                ];
            }
        }

        return $bestSellers;
    }

    public function getTopProduct(string $period = 'weekly'): ?array
    {
        $bestSellers = $this->getBestSellers($period, 1);
        return $bestSellers[0] ?? null;
    }

    public function getProductStats(ProductInterface $product, string $period = 'weekly'): ?array
    {
        $cache = $this->bestSellerRepository->findOneByProductAndPeriod(
            $product->getId(),
            $period
        );

        if (!$cache) {
            return null;
        }

        return [
            'total_sales' => $cache->getTotalSales(),
            'total_quantity' => $cache->getTotalQuantity(),
            'total_revenue' => $cache->getTotalRevenue(),
            'position' => $cache->getPosition(),
        ];
    }

    private function isProductAvailableForChannel(ProductInterface $product, ?int $channelId): bool
    {
        if (!$channelId) {
            return true;
        }

        foreach ($product->getChannels() as $channel) {
            if ($channel->getId() === $channelId) {
                return true;
            }
        }

        return false;
    }
}