<?php

declare(strict_types=1);

namespace SyliusYalidinePlugin\Webhook;

use Psr\Cache\CacheItemPoolInterface;
use Psr\Log\LoggerInterface;
use Sylius\Component\Core\Model\ShipmentInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

final readonly class YalidineWebhookAction
{
    /**
     * Yalidine statuses that mean the parcel was delivered.
     */
    private const DELIVERED_STATUSES = ['Livré', 'delivered'];

    /**
     * Yalidine statuses that mean the parcel was returned/cancelled.
     */
    private const RETURNED_STATUSES = [
        'Retourné au vendeur',
        'Annulé',
        'Echèc livraison',
        'returned',
        'canceled',
    ];

    public function __construct(
        private RepositoryInterface $shipmentRepository,
        private CacheItemPoolInterface $cache,
        private string $webhookToken,
        private LoggerInterface $logger,
    ) {
    }

    public function __invoke(Request $request): Response
    {
        // Validate webhook token
        if ($this->webhookToken !== '' && $request->headers->get('X-YALIDINE-WEBHOOK-TOKEN') !== $this->webhookToken) {
            return new JsonResponse(['success' => false, 'message' => 'Invalid webhook token.'], Response::HTTP_FORBIDDEN);
        }

        /** @var array<string, mixed> $payload */
        $payload = json_decode($request->getContent(), true) ?? [];

        // Yalidine can send single event or array of events
        $events = isset($payload['tracking']) ? [$payload] : (is_array($payload) ? $payload : []);

        foreach ($events as $event) {
            if (!is_array($event)) {
                continue;
            }

            $tracking = $event['tracking'] ?? null;
            $status = $event['last_status'] ?? $event['status'] ?? null;

            if (!is_string($tracking) || $tracking === '' || !is_string($status) || $status === '') {
                continue;
            }

            // Deduplication
            $dedupeKey = sprintf('yalidine_wh_%s_%s', $tracking, md5(json_encode($event) ?: ''));
            $cacheItem = $this->cache->getItem($dedupeKey);
            if ($cacheItem->isHit()) {
                continue;
            }

            /** @var ShipmentInterface|null $shipment */
            $shipment = $this->shipmentRepository->findOneBy(['tracking' => $tracking]);

            if ($shipment !== null) {
                if (in_array($status, self::DELIVERED_STATUSES, true)) {
                    $shipment->setState(ShipmentInterface::STATE_SHIPPED);
                    $this->shipmentRepository->add($shipment);
                    $this->logger->info('Yalidine webhook: shipment delivered.', ['tracking' => $tracking]);
                } elseif (in_array($status, self::RETURNED_STATUSES, true)) {
                    $shipment->setState(ShipmentInterface::STATE_CANCELLED);
                    $this->shipmentRepository->add($shipment);
                    $this->logger->info('Yalidine webhook: shipment returned/cancelled.', ['tracking' => $tracking, 'status' => $status]);
                } else {
                    $this->logger->info('Yalidine webhook: status update.', ['tracking' => $tracking, 'status' => $status]);
                }
            }

            $cacheItem->set(true);
            $cacheItem->expiresAfter(86400);
            $this->cache->save($cacheItem);
        }

        return new JsonResponse(['success' => true], Response::HTTP_OK);
    }
}
