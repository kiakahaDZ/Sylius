<?php

declare(strict_types=1);

namespace SyliusChargilyPlugin\Provider;

use Sylius\Component\Payment\Model\PaymentRequestInterface;
use Sylius\Component\Payment\PaymentRequestTransitions;

final class ChargilyStatusResolver
{
    public function resolveTransition(PaymentRequestInterface $paymentRequest): ?string
    {
        $payload = $paymentRequest->getPayload();

        if (!is_array($payload)) {
            return null;
        }

        $eventType = $payload['type'] ?? null;
        $status = $payload['data']['status'] ?? null;

        return match (true) {
            $eventType === 'checkout.paid' || $status === 'paid' => PaymentRequestTransitions::TRANSITION_COMPLETE,
            $eventType === 'checkout.failed' || $status === 'failed' => PaymentRequestTransitions::TRANSITION_FAIL,
            $eventType === 'checkout.expired' || $status === 'expired' => PaymentRequestTransitions::TRANSITION_CANCEL,
            $eventType === 'checkout.created' || $status === 'pending' => PaymentRequestTransitions::TRANSITION_PROCESS,
            default => null,
        };
    }
}
