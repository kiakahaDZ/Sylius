<?php

declare(strict_types=1);

namespace PrinterTheme\Twig;

use Sylius\Component\Channel\Context\ChannelContextInterface;
use Sylius\Component\Core\Model\ProductInterface;
use Sylius\Component\Core\Model\TaxonInterface;
use Sylius\Component\Core\Repository\ProductRepositoryInterface;
use Sylius\Component\Locale\Context\LocaleContextInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

final class HomepageProductExtension extends AbstractExtension
{
    public function __construct(
        private readonly ProductRepositoryInterface $productRepository,
        private readonly ChannelContextInterface $channelContext,
        private readonly LocaleContextInterface $localeContext,
    ) {
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('printer_latest_channel_products', [$this, 'getLatestChannelProducts']),
            new TwigFunction('printer_products_for_taxon', [$this, 'getProductsForTaxon']),
        ];
    }

    /**
     * @return array<int, ProductInterface>
     */
    public function getLatestChannelProducts(int $limit = 8): array
    {
        $channel = $this->channelContext->getChannel();
        $locale = $this->localeContext->getLocaleCode();

        return $this->productRepository->findLatestByChannel($channel, $locale, $limit);
    }

    /**
     * @return array<int, ProductInterface>
     */
    public function getProductsForTaxon(TaxonInterface $taxon, int $limit = 12, bool $includeDescendants = true): array
    {
        $channel = $this->channelContext->getChannel();
        $locale = $this->localeContext->getLocaleCode();

        $queryBuilder = $this->productRepository->createShopListQueryBuilder(
            $channel,
            $taxon,
            $locale,
            [],
            $includeDescendants,
        );

        return $queryBuilder
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult()
        ;
    }
}
