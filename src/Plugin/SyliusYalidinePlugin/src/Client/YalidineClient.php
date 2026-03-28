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

    public function getWilayas(): array
    {
        /** @var list<array<string, mixed>> $result */
        $result = $this->requestList('GET', '/wilayas');

        return $result;
    }

    public function getCommunes(int $wilayaId): array
    {
        /** @var list<array<string, mixed>> $result */
        $result = $this->requestList('GET', sprintf('/communes/?wilaya_id=%d', $wilayaId));

        return $result;
    }

    public function getFees(int $fromWilayaId, int $toWilayaId): array
    {
        return $this->request('GET', sprintf('/fees/?from_wilaya_id=%d&to_wilaya_id=%d', $fromWilayaId, $toWilayaId));
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

    /**
     * Like request() but returns a list (JSON array).
     * Handles paginated Yalidine responses ({data: [...], has_more, links}).
     *
     * @param array<string, mixed> $options
     *
     * @return list<array<string, mixed>>
     */
    private function requestList(string $method, string $path, array $options = []): array
    {
        $options['headers'] = [
            'Content-Type' => 'application/json',
            'X-API-ID' => $this->apiId,
            'X-API-TOKEN' => $this->apiToken,
        ];

        $allData = [];
        $url = rtrim($this->baseUrl, '/') . $path;
        $separator = str_contains($url, '?') ? '&' : '?';
        $page = 1;

        try {
            do {
                $pagedUrl = $url . $separator . 'page=' . $page . '&page_size=200';
                $response = $this->httpClient->request($method, $pagedUrl, $options);
                $statusCode = $response->getStatusCode();
                $content = $response->getContent(false);

                if ($statusCode >= 400) {
                    throw new \RuntimeException(sprintf('Yalidine API request failed with status %d and body: %s', $statusCode, $content));
                }

                /** @var array<string, mixed> $decoded */
                $decoded = json_decode($content, true, 512, JSON_THROW_ON_ERROR);

                // Handle paginated { data: [...], has_more: bool, ... }
                if (isset($decoded['data']) && is_array($decoded['data'])) {
                    $allData = array_merge($allData, $decoded['data']);
                    $hasMore = (bool) ($decoded['has_more'] ?? false);
                } else {
                    // Non-paginated: treat entire response as the list
                    $allData = array_merge($allData, $decoded);
                    $hasMore = false;
                }

                ++$page;
            } while ($hasMore && $page <= 50); // safety limit

            return $allData;
        } catch (ExceptionInterface|\JsonException $exception) {
            throw new \RuntimeException('Yalidine API request failed.', previous: $exception);
        }
    }
}
