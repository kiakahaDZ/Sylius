<?php

declare(strict_types=1);

namespace SyliusYalidinePlugin\Webhook;

use Psr\Cache\CacheItemPoolInterface;
use Sylius\Component\Core\Model\ShipmentInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

final readonly class YalidineWebhookAction
{
    public function __construct(
        private RepositoryInterface $shipmentRepository,
        private CacheItemPoolInterface $cache,
        private string $webhookToken,
    ) {
    }

    public function __invoke(Request $request): Response
    {
        if ($this->webhookToken !== '' && $request->headers->get('X-YALIDINE-WEBHOOK-TOKEN') !== $this->webhookToken) {
            return new JsonResponse(['success' => false, 'message' => 'Invalid webhook token.'], Response::HTTP_FORBIDDEN);
        }

        /** @var array<string, mixed> $payload */
        $payload = json_decode($request->getContent(), true) ?? [];
        $tracking = $payload['tracking'] ?? null;
        $status = $payload['status'] ?? null;

        if (!is_string($tracking) || $tracking === '' || !is_string($status) || $status === '') {
            return new JsonResponse(['success' => false, 'message' => 'Invalid payload.'], Response::HTTP_BAD_REQUEST);
        }

        $dedupeKey = sprintf('yalidine_webhook_%s_%s', $tracking, md5($request->getContent()));
        $cacheItem = $this->cache->getItem($dedupeKey);
        if ($cacheItem->isHit()) {
            return new JsonResponse(['success' => true, 'duplicate' => true], Response::HTTP_OK);
        }

        /** @var ShipmentInterface|null $shipment */
        $shipment = $this->shipmentRepository->findOneBy(['tracking' => $tracking]);
        if ($shipment !== null && $status === 'delivered') {
            $shipment->setState(ShipmentInterface::STATE_SHIPPED);
            $this->shipmentRepository->add($shipment);
        }

        $cacheItem->set(true);
        $cacheItem->expiresAfter(86400);
        $this->cache->save($cacheItem);

        return new JsonResponse(['success' => true], Response::HTTP_OK);
    }
}
