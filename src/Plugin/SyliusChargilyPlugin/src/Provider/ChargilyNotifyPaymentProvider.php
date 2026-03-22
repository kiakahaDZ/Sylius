<?php

declare(strict_types=1);

namespace SyliusChargilyPlugin\Provider;

use Sylius\Bundle\PaymentBundle\Attribute\AsNotifyPaymentProvider;
use Sylius\Bundle\PaymentBundle\Provider\NotifyPaymentProviderInterface;
use Sylius\Component\Core\Model\PaymentInterface;
use Sylius\Component\Core\Repository\PaymentRepositoryInterface;
use Sylius\Component\Payment\Model\PaymentMethodInterface;
use Symfony\Component\HttpFoundation\Request;
use Webmozart\Assert\Assert;

#[AsNotifyPaymentProvider]
final readonly class ChargilyNotifyPaymentProvider implements NotifyPaymentProviderInterface
{
    /** @param PaymentRepositoryInterface<PaymentInterface> $paymentRepository */
    public function __construct(private PaymentRepositoryInterface $paymentRepository)
    {
    }

    public function getPayment(Request $request, PaymentMethodInterface $paymentMethod): PaymentInterface
    {
        $payload = $request->toArray();
        $paymentId = $payload['data']['metadata']['payment_id'] ?? null;
        Assert::integerish($paymentId, 'Chargily webhook payload must contain a payment_id metadata field.');

        $payment = $this->paymentRepository->find((int) $paymentId);
        Assert::isInstanceOf($payment, PaymentInterface::class);

        $method = $payment->getMethod();
        Assert::notNull($method);
        Assert::same($method->getCode(), $paymentMethod->getCode());

        return $payment;
    }

    public function supports(Request $request, PaymentMethodInterface $paymentMethod): bool
    {
        $factoryName = $paymentMethod->getGatewayConfig()?->getFactoryName();

        return $factoryName === ChargilyGatewayFactory::FACTORY_NAME;
    }
}
