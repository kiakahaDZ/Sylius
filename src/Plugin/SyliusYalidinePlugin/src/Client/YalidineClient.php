<?php

declare(strict_types=1);

namespace SyliusYalidinePlugin\Client;

use Symfony\Contracts\HttpClient\Exception\ExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

final readonly class YalidineClient implements YalidineClientInterface
{
    public function __construct(
        private HttpClientInterface $httpClient,
        private string $apiId,
        private string $apiToken,
        private string $baseUrl,
    ) {
    }

    public function createParcel(array $payload): array
    {
        return $this->request('POST', '/parcels', ['json' => $payload]);
    }

    public function getParcel(string $tracking): array
    {
        return $this->request('GET', sprintf('/parcels/%s', urlencode($tracking)));
    }

    /**
     * @param array<string, mixed> $options
     *
     * @return array<string, mixed>
     */
    private function request(string $method, string $path, array $options = []): array
    {
        $options['headers'] = [
            'Content-Type' => 'application/json',
            'X-API-ID' => $this->apiId,
            'X-API-TOKEN' => $this->apiToken,
        ];

        try {
            $response = $this->httpClient->request($method, rtrim($this->baseUrl, '/') . $path, $options);
            $statusCode = $response->getStatusCode();
            $content = $response->getContent(false);

            if ($statusCode >= 400) {
                throw new \RuntimeException(sprintf('Yalidine API request failed with status %d and body: %s', $statusCode, $content));
            }

            /** @var array<string, mixed> $decoded */
            $decoded = json_decode($content, true, 512, JSON_THROW_ON_ERROR);

            return $decoded;
        } catch (ExceptionInterface|\JsonException $exception) {
            throw new \RuntimeException('Yalidine API request failed.', previous: $exception);
        }
    }
}
