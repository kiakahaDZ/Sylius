<?php

declare(strict_types=1);

namespace SyliusChargilyPlugin\CommandHandler;

use Sylius\Abstraction\StateMachine\StateMachineInterface;
use Sylius\Bundle\PaymentBundle\Provider\PaymentRequestProviderInterface;
use Sylius\Component\Payment\PaymentRequestTransitions;
use SyliusChargilyPlugin\Command\CaptureChargilyPaymentRequest;

final readonly class CaptureChargilyPaymentRequestHandler
{
    public function __construct(
        private PaymentRequestProviderInterface $paymentRequestProvider,
        private StateMachineInterface $stateMachine,
    ) {
    }

    public function __invoke(CaptureChargilyPaymentRequest $command): void
    {
        $paymentRequest = $this->paymentRequestProvider->provide($command);

        $this->stateMachine->apply(
            $paymentRequest,
            PaymentRequestTransitions::GRAPH,
            PaymentRequestTransitions::TRANSITION_PROCESS,
        );
    }
}
