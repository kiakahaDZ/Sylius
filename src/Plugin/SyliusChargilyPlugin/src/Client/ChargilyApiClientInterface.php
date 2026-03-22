<?php

declare(strict_types=1);

namespace SyliusChargilyPlugin\Client;

interface ChargilyApiClientInterface
{
    /**
     * @param array<string, mixed> $gatewayConfig
     * @param array<string, mixed> $payload
     *
     * @return array<string, mixed>
     */
    public function createCheckout(array $gatewayConfig, array $payload): array;

    /**
     * @param array<string, mixed> $gatewayConfig
     *
     * @return array<string, mixed>
     */
    public function getCheckout(array $gatewayConfig, string $checkoutId): array;
}
