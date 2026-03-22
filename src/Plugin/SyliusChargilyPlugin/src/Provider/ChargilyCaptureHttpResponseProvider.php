<?php

declare(strict_types=1);

namespace SyliusChargilyPlugin\Provider;

use Sylius\Bundle\PaymentBundle\Provider\HttpResponseProviderInterface;
use Sylius\Bundle\ResourceBundle\Controller\RequestConfiguration;
use Doctrine\ORM\EntityManagerInterface;
use Sylius\Component\Payment\Model\GatewayConfigInterface;
use Sylius\Component\Payment\Model\PaymentRequestInterface;
use SyliusChargilyPlugin\Client\ChargilyApiClientInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Webmozart\Assert\Assert;

final readonly class ChargilyCaptureHttpResponseProvider implements HttpResponseProviderInterface
{
    public function __construct(
        private ChargilyApiClientInterface $chargilyApiClient,
        private ChargilyCheckoutPayloadProvider $checkoutPayloadProvider,
        private EntityManagerInterface $paymentRequestManager,
    ) {
    }

    public function supports(RequestConfiguration $requestConfiguration, PaymentRequestInterface $paymentRequest): bool
    {
        return $paymentRequest->getAction() === PaymentRequestInterface::ACTION_CAPTURE;
    }

    public function getResponse(RequestConfiguration $requestConfiguration, PaymentRequestInterface $paymentRequest): Response
    {
        $method = $paymentRequest->getMethod();
        $gatewayConfig = $method->getGatewayConfig();
        Assert::isInstanceOf($gatewayConfig, GatewayConfigInterface::class);

        /** @var array<string, mixed> $config */
        $config = $gatewayConfig->getConfig();
        $payload = $this->checkoutPayloadProvider->provide($paymentRequest, $config);
        $checkout = $this->chargilyApiClient->createCheckout($config, $payload);

        $paymentRequest->setResponseData($checkout);
        $this->paymentRequestManager->flush();

        $checkoutUrl = $checkout['url'] ?? null;
        if (!is_string($checkoutUrl) || $checkoutUrl === '') {
            throw new \RuntimeException('Chargily checkout URL is missing from the API response.');
        }

        return new RedirectResponse($checkoutUrl);
    }
}
