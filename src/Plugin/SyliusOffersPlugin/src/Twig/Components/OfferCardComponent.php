<?php

declare(strict_types=1);

namespace SyliusOffersPlugin\Twig\Components;

use SyliusOffersPlugin\Entity\Offer;
use SyliusOffersPlugin\Repository\OfferRepository;
use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent('offer_card', template: '@SyliusOffersPlugin/shop/components/offer_card.html.twig')]
class OfferCardComponent
{
    public Offer $offer;
    
    private OfferRepository $offerRepository;
    
    public function __construct(OfferRepository $offerRepository)
    {
        $this->offerRepository = $offerRepository;
    }
    
    public function getOffer(): Offer
    {
        return $this->offer;
    }
    
    public function getBackgroundColor(): string
    {
        return $this->offer->getBackgroundColor() ?? '#f8f9fa';
    }
    
    public function getTextColor(): string
    {
        return $this->offer->getTextColor() ?? '#212529';
    }
    
    public function getButtonText(): string
    {
        return $this->offer->getButtonText() ?? 'View Offer';
    }
    
    public function getButtonColor(): string
    {
        return $this->offer->getButtonColor() ?? '#007bff';
    }
    
    public function hasCountdown(): bool
    {
        return $this->offer->getEndDate() !== null && 
               $this->offer->getEndDate() > new \DateTime();
    }
    
    public function getRemainingTime(): ?array
    {
        if (!$this->hasCountdown()) {
            return null;
        }
        
        $now = new \DateTime();
        $end = $this->offer->getEndDate();
        $diff = $end->diff($now);
        
        return [
            'days' => $diff->d,
            'hours' => $diff->h,
            'minutes' => $diff->i,
            'seconds' => $diff->s,
            'total_seconds' => ($diff->d * 86400) + ($diff->h * 3600) + ($diff->i * 60) + $diff->s,
        ];
    }
}