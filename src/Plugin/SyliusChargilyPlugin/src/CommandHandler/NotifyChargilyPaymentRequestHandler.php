<?php

declare(strict_types = 1)
;

namespace SyliusChargilyPlugin\CommandHandler;

use Sylius\Abstraction\StateMachine\StateMachineInterface;
use Sylius\Bundle\PaymentBundle\Provider\PaymentRequestProviderInterface;
use Sylius\Component\Payment\PaymentRequestTransitions;
use Sylius\Component\Payment\PaymentTransitions;
use SyliusChargilyPlugin\Command\NotifyChargilyPaymentRequest;
use SyliusChargilyPlugin\Provider\ChargilyStatusResolver;

final class NotifyChargilyPaymentRequestHandler
{
    public function __construct(private
        PaymentRequestProviderInterface $paymentRequestProvider, private
        StateMachineInterface $stateMachine, private
        ChargilyStatusResolver $statusResolver,
        )
    {
    }

    public function __invoke(NotifyChargilyPaymentRequest $command): void
    {
        $paymentRequest = $this->paymentRequestProvider->provide($command);
        $transition = $this->statusResolver->resolveTransition($paymentRequest);

        if ($transition === null) {
            return;
        }

        $this->stateMachine->apply($paymentRequest, PaymentRequestTransitions::GRAPH, $transition);

        $payment = $paymentRequest->getPayment();
        $paymentTransition = match ($transition) {
                PaymentRequestTransitions::TRANSITION_COMPLETE => PaymentTransitions::TRANSITION_COMPLETE,
                PaymentRequestTransitions::TRANSITION_FAIL => PaymentTransitions::TRANSITION_FAIL,
                PaymentRequestTransitions::TRANSITION_CANCEL => PaymentTransitions::TRANSITION_CANCEL,
                default => PaymentTransitions::TRANSITION_PROCESS,
            };

        $this->stateMachine->apply($payment, PaymentTransitions::GRAPH, $paymentTransition);
    }
}
