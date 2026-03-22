<?php

declare(strict_types=1);

namespace Tests\Unit\Plugin\SyliusChargilyPlugin\Provider;

use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Sylius\Bundle\CoreBundle\OrderPay\Provider\UrlProviderInterface;
use Sylius\Component\Core\Model\OrderInterface;
use Sylius\Component\Payment\Model\PaymentInterface;
use Sylius\Component\Payment\Model\PaymentMethodInterface;
use Sylius\Component\Payment\Model\PaymentRequestInterface;
use SyliusChargilyPlugin\Provider\ChargilyCheckoutPayloadProvider;
use SyliusChargilyPlugin\Provider\ChargilyWebhookUrlProvider;

final class ChargilyCheckoutPayloadProviderTest extends TestCase
{
    private UrlProviderInterface&MockObject $afterPayUrlProvider;

    private ChargilyWebhookUrlProvider&MockObject $webhookUrlProvider;

    protected function setUp(): void
    {
        $this->afterPayUrlProvider = $this->createMock(UrlProviderInterface::class);
        $this->webhookUrlProvider = $this->createMock(ChargilyWebhookUrlProvider::class);
    }

    #[Test]
    public function it_builds_a_checkout_payload_from_the_payment_request(): void
    {
        $order = $this->createMock(OrderInterface::class);
        $order->method('getNumber')->willReturn('000001');

        $paymentMethod = $this->createMock(PaymentMethodInterface::class);
        $paymentMethod->method('getCode')->willReturn('chargily');

        $payment = $this->createMock(PaymentInterface::class);
        $payment->method('getAmount')->willReturn(50000);
        $payment->method('getCurrencyCode')->willReturn('DZD');
        $payment->method('getId')->willReturn(12);
        $payment->method('getOrder')->willReturn($order);

        $paymentRequest = $this->createMock(PaymentRequestInterface::class);
        $paymentRequest->method('getPayment')->willReturn($payment);
        $paymentRequest->method('getMethod')->willReturn($paymentMethod);
        $paymentRequest->method('getHash')->willReturn('hash');

        $this->afterPayUrlProvider->method('getUrl')->with($paymentRequest)->willReturn('/after-pay/hash');
        $this->webhookUrlProvider->method('getUrl')->with($paymentMethod)->willReturn('https://example.com/chargily/webhook/chargily');

        $provider = new ChargilyCheckoutPayloadProvider($this->afterPayUrlProvider, $this->webhookUrlProvider);
        $payload = $provider->provide($paymentRequest, [
            'fees_allocation' => 'merchant',
            'locale' => 'ar',
            'payment_method' => 'edahabia',
        ]);

        $this->assertSame(50000, $payload['amount']);
        $this->assertSame('DZD', $payload['currency']);
        $this->assertSame('https://example.com/chargily/webhook/chargily', $payload['webhook_url']);
        $this->assertSame('edahabia', $payload['payment_method']);
        $this->assertSame('12', $payload['metadata']['payment_id']);
    }
}
