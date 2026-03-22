<?php

declare(strict_types = 1)
;

namespace SyliusOffersPlugin\Twig;

use SyliusOffersPlugin\Repository\OfferRepository;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class OfferExtension extends AbstractExtension
{
    private OfferRepository $offerRepository;

    public function __construct(OfferRepository $offerRepository)
    {
        $this->offerRepository = $offerRepository;
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('sylius_offers', [$this, 'getActiveOffers']),
            new TwigFunction('sylius_offers_by_type', [$this, 'getOffersByType']),
            new TwigFunction('sylius_upcoming_offers', [$this, 'getUpcomingOffers']),
            new TwigFunction('sylius_expiring_offers', [$this, 'getExpiringOffers']),
            new TwigFunction('sylius_best_offers', [$this, 'getBestPerformingOffers']),
        ];
    }

    public function getActiveOffers(?string $url = null, ?string $locale = null): array
    {
        return $this->offerRepository->findActiveOffers(null, $url, $locale);
    }

    public function getOffersByType(string $type, int $limit = 10): array
    {
        return $this->offerRepository->findActiveByType($type, $limit);
    }

    public function getUpcomingOffers(int $days = 7): array
    {
        return $this->offerRepository->findUpcomingOffers($days);
    }

    public function getExpiringOffers(int $days = 7): array
    {
        return $this->offerRepository->findExpiringOffers($days);
    }

    public function getBestPerformingOffers(int $limit = 10): array
    {
        return $this->offerRepository->findBestPerformingOffers($limit);
    }
}