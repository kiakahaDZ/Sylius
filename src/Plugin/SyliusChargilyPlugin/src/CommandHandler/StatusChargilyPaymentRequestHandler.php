<?php

declare(strict_types = 1)
;

namespace SyliusChargilyPlugin\CommandHandler;

use Sylius\Abstraction\StateMachine\StateMachineInterface;
use Sylius\Bundle\PaymentBundle\Provider\PaymentRequestProviderInterface;
use Sylius\Component\Payment\PaymentRequestTransitions;
use SyliusChargilyPlugin\Command\StatusChargilyPaymentRequest;
use SyliusChargilyPlugin\Provider\ChargilyStatusResolver;

final class StatusChargilyPaymentRequestHandler
{
    public function __construct(private
        PaymentRequestProviderInterface $paymentRequestProvider, private
        StateMachineInterface $stateMachine, private
        ChargilyStatusResolver $statusResolver,
        )
    {
    }

    public function __invoke(StatusChargilyPaymentRequest $command): void
    {
        $paymentRequest = $this->paymentRequestProvider->provide($command);
        $transition = $this->statusResolver->resolveTransition($paymentRequest);

        if ($transition === null) {
            return;
        }

        $this->stateMachine->apply($paymentRequest, PaymentRequestTransitions::GRAPH, $transition);
    }
}
