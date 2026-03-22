<?php

declare(strict_types=1);

namespace SyliusOffersPlugin\Twig\Components;

use SyliusOffersPlugin\Entity\Offer;
use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent('offer_banner', template: '@SyliusOffersPlugin/shop/components/offer_banner.html.twig')]
class OfferBannerComponent
{
    public Offer $offer;
    
    public function getOffer(): Offer
    {
        return $this->offer;
    }
    
    public function getBackgroundColor(): string
    {
        return $this->offer->getBackgroundColor() ?? 'linear-gradient(135deg, #667eea 0%, #764ba2 100%)';
    }
    
    public function getTextColor(): string
    {
        return $this->offer->getTextColor() ?? '#ffffff';
    }
    
    public function getButtonText(): string
    {
        return $this->offer->getButtonText() ?? 'Shop Now';
    }
}