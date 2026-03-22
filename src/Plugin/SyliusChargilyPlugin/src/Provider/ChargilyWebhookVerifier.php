<?php

declare(strict_types=1);

namespace SyliusChargilyPlugin\Provider;

final class ChargilyWebhookVerifier
{
    /**
     * @param array<string, mixed> $gatewayConfig
     */
    public function verify(string $payload, ?string $signature, array $gatewayConfig): bool
    {
        if ($signature === null || $signature === '') {
            return false;
        }

        $secretKey = (string) ($gatewayConfig['secret_key'] ?? '');
        if ($secretKey === '') {
            return false;
        }

        $computedSignature = hash_hmac('sha256', $payload, $secretKey);

        return hash_equals($computedSignature, $signature);
    }
}
