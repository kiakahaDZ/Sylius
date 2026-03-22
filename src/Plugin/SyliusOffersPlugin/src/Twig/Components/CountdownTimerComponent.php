<?php

declare(strict_types=1);

namespace SyliusOffersPlugin\Twig\Components;

use SyliusOffersPlugin\Entity\Offer;
use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent('countdown_timer', template: '@SyliusOffersPlugin/shop/components/countdown_timer.html.twig')]
class CountdownTimerComponent
{
    public Offer $offer;
    
    public function getRemainingTime(): ?array
    {
        if (!$this->offer->getEndDate()) {
            return null;
        }
        
        $now = new \DateTime();
        $end = $this->offer->getEndDate();
        
        if ($end <= $now) {
            return ['expired' => true];
        }
        
        $diff = $end->diff($now);
        
        return [
            'days' => $diff->d,
            'hours' => $diff->h,
            'minutes' => $diff->i,
            'seconds' => $diff->s,
            'total_seconds' => ($diff->d * 86400) + ($diff->h * 3600) + ($diff->i * 60) + $diff->s,
            'expired' => false,
        ];
    }
    
    public function getEndDateTimestamp(): int
    {
        return $this->offer->getEndDate() ? $this->offer->getEndDate()->getTimestamp() : 0;
    }
}
