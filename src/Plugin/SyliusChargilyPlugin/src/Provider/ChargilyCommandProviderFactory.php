<?php

declare(strict_types=1);

namespace SyliusChargilyPlugin\Provider;

use Sylius\Bundle\PaymentBundle\CommandProvider\PaymentRequestCommandProviderInterface;
use Sylius\Component\Payment\Model\PaymentRequestInterface;
use SyliusChargilyPlugin\Command\CaptureChargilyPaymentRequest;
use SyliusChargilyPlugin\Command\NotifyChargilyPaymentRequest;
use SyliusChargilyPlugin\Command\StatusChargilyPaymentRequest;

final class ChargilyCommandProviderFactory
{
    public static function createCapture(): PaymentRequestCommandProviderInterface
    {
        return new class () implements PaymentRequestCommandProviderInterface {
            public function supports(PaymentRequestInterface $paymentRequest): bool
            {
                return $paymentRequest->getAction() === PaymentRequestInterface::ACTION_CAPTURE;
            }

            public function provide(PaymentRequestInterface $paymentRequest): object
            {
                return new CaptureChargilyPaymentRequest((string) $paymentRequest->getId());
            }
        };
    }

    public static function createStatus(): PaymentRequestCommandProviderInterface
    {
        return new class () implements PaymentRequestCommandProviderInterface {
            public function supports(PaymentRequestInterface $paymentRequest): bool
            {
                return $paymentRequest->getAction() === PaymentRequestInterface::ACTION_STATUS;
            }

            public function provide(PaymentRequestInterface $paymentRequest): object
            {
                return new StatusChargilyPaymentRequest((string) $paymentRequest->getId());
            }
        };
    }

    public static function createNotify(): PaymentRequestCommandProviderInterface
    {
        return new class () implements PaymentRequestCommandProviderInterface {
            public function supports(PaymentRequestInterface $paymentRequest): bool
            {
                return $paymentRequest->getAction() === PaymentRequestInterface::ACTION_NOTIFY;
            }

            public function provide(PaymentRequestInterface $paymentRequest): object
            {
                return new NotifyChargilyPaymentRequest((string) $paymentRequest->getId());
            }
        };
    }
}
