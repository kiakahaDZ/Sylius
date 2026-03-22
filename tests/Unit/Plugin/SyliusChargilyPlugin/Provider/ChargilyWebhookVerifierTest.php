<?php

declare(strict_types=1);

namespace Tests\Unit\Plugin\SyliusChargilyPlugin\Provider;

use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use SyliusChargilyPlugin\Provider\ChargilyWebhookVerifier;

final class ChargilyWebhookVerifierTest extends TestCase
{
    #[Test]
    public function it_verifies_a_valid_signature(): void
    {
        $payload = json_encode(['type' => 'checkout.paid'], JSON_THROW_ON_ERROR);
        $secretKey = 'test_secret';
        $signature = hash_hmac('sha256', $payload, $secretKey);

        $verifier = new ChargilyWebhookVerifier();

        $this->assertTrue($verifier->verify($payload, $signature, ['secret_key' => $secretKey]));
    }

    #[Test]
    public function it_rejects_an_invalid_signature(): void
    {
        $verifier = new ChargilyWebhookVerifier();

        $this->assertFalse($verifier->verify('{}', 'invalid', ['secret_key' => 'test_secret']));
    }
}
