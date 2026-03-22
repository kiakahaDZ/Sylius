<?php

declare(strict_types=1);

namespace SyliusChargilyPlugin\Command;

use Sylius\Bundle\PaymentBundle\Command\PaymentRequestHashAwareInterface;

final readonly class NotifyChargilyPaymentRequest implements PaymentRequestHashAwareInterface
{
    public function __construct(private string $hash)
    {
    }

    public function getHash(): string
    {
        return $this->hash;
    }
}
