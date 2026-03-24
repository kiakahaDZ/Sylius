<?php

declare(strict_types = 1)
;

namespace SyliusChargilyPlugin\Provider;

use Sylius\Component\Payment\Model\PaymentMethodInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\RouterInterface;

final class ChargilyWebhookUrlProvider
{
    public function __construct(private RouterInterface $router)
    {
    }

    public function getUrl(PaymentMethodInterface $paymentMethod, int $referenceType = UrlGeneratorInterface::ABSOLUTE_URL): string
    {
        return $this->router->generate('sylius_chargily_webhook', [
            'code' => $paymentMethod->getCode(),
        ], $referenceType);
    }
}
