<?php

declare(strict_types=1);

namespace SyliusChargilyPlugin\Provider;

use Sylius\Bundle\CoreBundle\OrderPay\Provider\UrlProviderInterface;
use Sylius\Component\Core\Model\OrderInterface;
use Sylius\Component\Payment\Model\PaymentRequestInterface;
use Webmozart\Assert\Assert;

final readonly class ChargilyCheckoutPayloadProvider
{
    public function __construct(
        private UrlProviderInterface $afterPayUrlProvider,
        private ChargilyWebhookUrlProvider $chargilyWebhookUrlProvider,
    ) {
    }

    /**
     * @param array<string, mixed> $gatewayConfig
     *
     * @return array<string, mixed>
     */
    public function provide(PaymentRequestInterface $paymentRequest, array $gatewayConfig): array
    {
        $payment = $paymentRequest->getPayment();
        $order = $payment->getOrder();
        Assert::isInstanceOf($order, OrderInterface::class);

        $supportedMethod = (string) ($gatewayConfig['payment_method'] ?? '');
        $orderNumber = $order->getNumber() ?? (string) $order->getId();

        return [
            'amount' => $payment->getAmount(),
            'chargily_pay_fees_allocation' => $gatewayConfig['fees_allocation'] ?? 'merchant',
            'currency' => $payment->getCurrencyCode(),
            'description' => sprintf('Order %s payment', $orderNumber),
            'locale' => $gatewayConfig['locale'] ?? 'ar',
            'metadata' => [
                'payment_id' => (string) $payment->getId(),
                'payment_request_hash' => (string) $paymentRequest->getHash(),
            ],
            'payment_method' => $supportedMethod !== '' ? $supportedMethod : null,
            'success_url' => $this->afterPayUrlProvider->getUrl($paymentRequest),
            'failure_url' => $this->afterPayUrlProvider->getUrl($paymentRequest),
            'webhook_url' => $this->chargilyWebhookUrlProvider->getUrl($paymentRequest->getMethod()),
        ];
    }
}
