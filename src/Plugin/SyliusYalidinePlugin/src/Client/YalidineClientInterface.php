<?php

declare(strict_types=1);

namespace SyliusYalidinePlugin\Client;

interface YalidineClientInterface
{
    /**
     * @param array<string, mixed> $payload
     *
     * @return array<string, mixed>
     */
    public function createParcel(array $payload): array;

    /**
     * @return array<string, mixed>
     */
    public function getParcel(string $tracking): array;

    /**
     * @return list<array<string, mixed>>
     */
    public function getWilayas(): array;

    /**
     * @return list<array<string, mixed>>
     */
    public function getCommunes(int $wilayaId): array;

    /**
     * @return array<string, mixed>
     */
    public function getFees(int $fromWilayaId, int $toWilayaId): array;
}
