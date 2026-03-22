<?php

declare(strict_types = 1)
;

namespace SyliusBestSellerPlugin\Service;

use Symfony\Contracts\Cache\CacheInterface;

class BestSellerCacheManager
{
    private CacheInterface $cache;
    private BestSellerProvider $bestSellerProvider;
    private \Doctrine\ORM\EntityManagerInterface $entityManager;
    private int $cacheTtl;

    public function __construct(
        CacheInterface $cache,
        BestSellerProvider $bestSellerProvider,
        \Doctrine\ORM\EntityManagerInterface $entityManager,
        int $cacheTtl = 3600
        )
    {
        $this->cache = $cache;
        $this->bestSellerProvider = $bestSellerProvider;
        $this->entityManager = $entityManager;
        $this->cacheTtl = $cacheTtl;
    }

    public function getProvider(): BestSellerProvider
    {
        return $this->bestSellerProvider;
    }

    public function getBestSellers(?string $period = null, ?int $limit = null, ?int $channelId = null): array
    {
        $config = $this->entityManager->getRepository(\SyliusBestSellerPlugin\Entity\BestSellerConfig::class)->findOneBy([]);
        
        $period = $period ?? ($config ? $config->getPeriod() : 'weekly');
        $limit = $limit ?? ($config ? $config->getNumberOfProducts() : 10);
        $ttl = $config ? $config->getCacheTtl() : $this->cacheTtl;

        $cacheKey = $this->generateCacheKey($period, $limit, $channelId);

        return $this->cache->get($cacheKey, function () use ($period, $limit, $channelId) {
            return $this->bestSellerProvider->getBestSellers($period, $limit, $channelId);
        }, $ttl);
    }

    public function invalidateCache(string $period = null, ?int $channelId = null): void
    {
        if ($period) {
            $pattern = $this->generateCacheKey($period, '*', $channelId);
            $this->cache->delete($pattern);
        }
        else {
            // Clear all best seller cache
            $this->cache->clear();
        }
    }

    private function generateCacheKey(string $period, int $limit, ?int $channelId): string
    {
        return sprintf('best_sellers_%s_%d_%d', $period, $limit, $channelId ?? 0);
    }
}