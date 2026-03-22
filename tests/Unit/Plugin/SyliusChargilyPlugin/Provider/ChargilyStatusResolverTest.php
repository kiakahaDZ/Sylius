<?php

declare(strict_types=1);

namespace Tests\Unit\Plugin\SyliusChargilyPlugin\Provider;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Sylius\Component\Payment\Model\PaymentRequestInterface;
use SyliusChargilyPlugin\Provider\ChargilyStatusResolver;

final class ChargilyStatusResolverTest extends TestCase
{
    #[Test]
    #[DataProvider('payloads')]
    public function it_resolves_transition_from_payload(array $payload, ?string $expectedTransition): void
    {
        $paymentRequest = $this->createMock(PaymentRequestInterface::class);
        $paymentRequest->method('getPayload')->willReturn($payload);

        $resolver = new ChargilyStatusResolver();

        $this->assertSame($expectedTransition, $resolver->resolveTransition($paymentRequest));
    }

    public static function payloads(): iterable
    {
        yield 'paid event' => [['type' => 'checkout.paid'], 'complete'];
        yield 'failed status' => [['data' => ['status' => 'failed']], 'fail'];
        yield 'expired status' => [['data' => ['status' => 'expired']], 'cancel'];
        yield 'pending status' => [['data' => ['status' => 'pending']], 'process'];
        yield 'unknown payload' => [['type' => 'checkout.unknown'], null];
    }
}
