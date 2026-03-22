<?php

declare(strict_types = 1)
;

namespace SyliusBestSellerPlugin\Twig;

use SyliusBestSellerPlugin\Service\BestSellerCacheManager;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class BestSellerExtension extends AbstractExtension
{
    private BestSellerCacheManager $bestSellerCacheManager;

    public function __construct(BestSellerCacheManager $bestSellerCacheManager)
    {
        $this->bestSellerCacheManager = $bestSellerCacheManager;
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('sylius_best_sellers', [$this, 'getBestSellers']),
            new TwigFunction('sylius_top_product', [$this, 'getTopProduct']),
            new TwigFunction('sylius_product_stats', [$this, 'getProductStats']),
        ];
    }

    public function getBestSellers(string $period = 'weekly', int $limit = 10, ?int $channelId = null): array
    {
        return $this->bestSellerCacheManager->getBestSellers($period, $limit, $channelId);
    }

    public function getTopProduct(string $period = 'weekly'): ?array
    {
        $bestSellers = $this->getBestSellers($period, 1);
        return $bestSellers[0] ?? null;
    }

    public function getProductStats($product, string $period = 'weekly'): ?array
    {
        if (!$product || !$product->getId()) {
            return null;
        }

        $provider = $this->bestSellerCacheManager->getProvider();
        return $provider->getProductStats($product, $period);
    }
}