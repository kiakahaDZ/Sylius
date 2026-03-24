<?php

declare(strict_types=1);

namespace SyliusChargilyPlugin\Command;

use Sylius\Bundle\PaymentBundle\Command\PaymentRequestHashAwareInterface;

final class StatusChargilyPaymentRequest implements PaymentRequestHashAwareInterface
{
    public function __construct(private string $hash)
    {
    }

    public function getHash(): string
    {
        return $this->hash;
    }

    public function setHash(string $hash): void
    {
        $this->hash = $hash;
    }
}
