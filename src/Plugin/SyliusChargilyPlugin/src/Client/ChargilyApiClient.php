<?php

declare(strict_types=1);

namespace SyliusChargilyPlugin\Client;

use Symfony\Contracts\HttpClient\Exception\ExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

final class ChargilyApiClient implements ChargilyApiClientInterface
{
    private const BASE_URI_BY_MODE = [
        'live' => 'https://pay.chargily.net/api/v2',
        'test' => 'https://pay.chargily.net/test/api/v2',
    ];

    public function __construct(private HttpClientInterface $httpClient)
    {
    }

    public function createCheckout(array $gatewayConfig, array $payload): array
    {
        return $this->request($gatewayConfig, 'POST', '/checkouts', $payload);
    }

    public function getCheckout(array $gatewayConfig, string $checkoutId): array
    {
        return $this->request($gatewayConfig, 'GET', sprintf('/checkouts/%s', $checkoutId));
    }

    /**
     * @param array<string, mixed> $gatewayConfig
     * @param array<string, mixed> $payload
     *
     * @return array<string, mixed>
     */
    private function request(array $gatewayConfig, string $method, string $path, array $payload = []): array
    {
        $isSandbox = (bool) ($gatewayConfig['sandbox'] ?? true);
        $baseUri = $isSandbox ? self::BASE_URI_BY_MODE['test'] : self::BASE_URI_BY_MODE['live'];
        
        $secretKey = $isSandbox
            ? (string) ($gatewayConfig['test_secret_key'] ?? '')
            : (string) ($gatewayConfig['live_secret_key'] ?? '');

        if ($secretKey === '') {
            throw new \InvalidArgumentException('Chargily secret key is required.');
        }

        try {
            $response = $this->httpClient->request($method, $baseUri . $path, [
                'auth_bearer' => $secretKey,
                'headers' => [
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json',
                ],
                'json' => $payload,
            ]);

            /** @var array<string, mixed> $data */
            $data = $response->toArray(false);
        } catch (ExceptionInterface $exception) {
            throw new \RuntimeException('Chargily API request failed.', previous: $exception);
        }

        return $data;
    }
}
