<?php

declare(strict_types=1);

namespace Plugin\SyliusBannerPlugin\Controller\Shop;

use Plugin\SyliusBannerPlugin\Repository\BannerRepositoryInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

final class BannerController extends AbstractController
{
    public function __construct(
        private readonly BannerRepositoryInterface $bannerRepository,
    ) {
    }

    public function heroAction(string $position = 'hero'): Response
    {
        $banners = $this->bannerRepository->findByPosition($position);

        return $this->render('@SyliusBannerPlugin/shop/banner/hero.html.twig', [
            'banners' => $banners,
        ]);
    }

    public function promotionalStripAction(string $position = 'promo'): Response
    {
        $banners = $this->bannerRepository->findByPosition($position);

        return $this->render('@SyliusBannerPlugin/shop/banner/promotional_strip.html.twig', [
            'banners' => $banners,
        ]);
    }
}
