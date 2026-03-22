<?php

declare(strict_types=1);

namespace SyliusChargilyPlugin\Controller;

use Sylius\Bundle\PaymentBundle\Announcer\PaymentRequestAnnouncerInterface;
use Sylius\Bundle\PaymentBundle\Processor\NotifyPayloadProcessorInterface;
use Sylius\Bundle\PaymentBundle\Provider\NotifyPaymentProviderInterface;
use Sylius\Bundle\PaymentBundle\Provider\NotifyResponseProviderInterface;
use Sylius\Component\Payment\Factory\PaymentRequestFactoryInterface;
use Sylius\Component\Payment\Model\GatewayConfigInterface;
use Sylius\Component\Payment\Model\PaymentMethodInterface;
use Sylius\Component\Payment\Model\PaymentRequestInterface;
use Sylius\Component\Payment\Repository\PaymentMethodRepositoryInterface;
use Sylius\Component\Payment\Repository\PaymentRequestRepositoryInterface;
use SyliusChargilyPlugin\Provider\ChargilyWebhookVerifier;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Webmozart\Assert\Assert;

final readonly class ChargilyWebhookAction
{
    /**
     * @param PaymentMethodRepositoryInterface<PaymentMethodInterface> $paymentMethodRepository
     * @param PaymentRequestFactoryInterface<PaymentRequestInterface> $paymentRequestFactory
     * @param PaymentRequestRepositoryInterface<PaymentRequestInterface> $paymentRequestRepository
     */
    public function __construct(
        private PaymentMethodRepositoryInterface $paymentMethodRepository,
        private NotifyPaymentProviderInterface $notifyPaymentProvider,
        private PaymentRequestFactoryInterface $paymentRequestFactory,
        private NotifyPayloadProcessorInterface $notifyPayloadProcessor,
        private PaymentRequestRepositoryInterface $paymentRequestRepository,
        private PaymentRequestAnnouncerInterface $paymentRequestAnnouncer,
        private NotifyResponseProviderInterface $notifyResponseProvider,
        private ChargilyWebhookVerifier $webhookVerifier,
    ) {
    }

    public function __invoke(Request $request, string $code): Response
    {
        $paymentMethod = $this->paymentMethodRepository->findOneBy(['code' => $code]);
        if (!$paymentMethod instanceof PaymentMethodInterface) {
            throw new NotFoundHttpException(sprintf('No payment method found with code "%s".', $code));
        }

        $gatewayConfig = $paymentMethod->getGatewayConfig();
        Assert::isInstanceOf($gatewayConfig, GatewayConfigInterface::class);

        /** @var array<string, mixed> $config */
        $config = $gatewayConfig->getConfig();
        $payload = $request->getContent();
        $signature = $request->headers->get('signature');

        if (!$this->webhookVerifier->verify($payload, $signature, $config)) {
            throw new AccessDeniedHttpException('Invalid Chargily webhook signature.');
        }

        $request->request->replace($request->toArray());

        $payment = $this->notifyPaymentProvider->getPayment($request, $paymentMethod);
        $paymentRequest = $this->paymentRequestFactory->create($payment, $paymentMethod);
        $paymentRequest->setAction(PaymentRequestInterface::ACTION_NOTIFY);

        $this->notifyPayloadProcessor->process($paymentRequest, $request);
        $this->paymentRequestRepository->add($paymentRequest);
        $this->paymentRequestAnnouncer->dispatchPaymentRequestCommand($paymentRequest);

        $response = $this->notifyResponseProvider->provide($paymentRequest);

        return $response instanceof JsonResponse ? $response : new JsonResponse([], Response::HTTP_OK);
    }
}
