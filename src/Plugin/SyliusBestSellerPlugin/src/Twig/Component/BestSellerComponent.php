<?php

declare(strict_types=1);

namespace SyliusBestSellerPlugin\Twig\Component;

use Sylius\Component\Channel\Context\ChannelContextInterface;
use SyliusBestSellerPlugin\Service\BestSellerCacheManager;
use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent(name: 'sylius_best_seller:shop:best_sellers', template: '@SyliusBestSellerPlugin/shop/home/best_sellers.html.twig')]
final class BestSellerComponent
{
    private BestSellerCacheManager $bestSellerCacheManager;
    private ChannelContextInterface $channelContext;

    public string $period = 'weekly';
    public int $limit = 8;

    public function __construct(
        BestSellerCacheManager $bestSellerCacheManager,
        ChannelContextInterface $channelContext
    ) {
        $this->bestSellerCacheManager = $bestSellerCacheManager;
        $this->channelContext = $channelContext;
    }

    public function getBestSellers(): array
    {
        $channel = $this->channelContext->getChannel();
        return $this->bestSellerCacheManager->getBestSellers($this->period, $this->limit, (int) $channel->getId());
    }
}
