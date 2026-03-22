<?php

declare(strict_types = 1)
;

namespace SyliusBestSellerPlugin\EventListener;

use Sylius\Component\Core\Model\OrderInterface;
use Sylius\Component\Core\OrderPaymentStates;
use SyliusBestSellerPlugin\Service\BestSellerCalculator;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\EventDispatcher\GenericEvent;

class OrderCompleteListener implements EventSubscriberInterface
{
    private BestSellerCalculator $bestSellerCalculator;

    public function __construct(BestSellerCalculator $bestSellerCalculator)
    {
        $this->bestSellerCalculator = $bestSellerCalculator;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            'sylius.order.post_complete' => 'onOrderComplete',
        ];
    }

    public function onOrderComplete(GenericEvent $event): void
    {
        $order = $event->getSubject();

        if (!$order instanceof OrderInterface) {
            return;
        }

        if ($order->getPaymentState() === OrderPaymentStates::STATE_PAID) {
            $this->bestSellerCalculator->calculateForOrder($order);
        }
    }
}