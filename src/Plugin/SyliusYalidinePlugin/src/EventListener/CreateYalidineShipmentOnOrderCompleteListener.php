<?php

declare(strict_types=1);

namespace SyliusYalidinePlugin\EventListener;

use Psr\Log\LoggerInterface;
use Sylius\Component\Core\Model\OrderInterface;
use Sylius\Component\Core\Model\ShipmentInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;
use SyliusYalidinePlugin\Client\YalidineClientInterface;
use SyliusYalidinePlugin\Provider\YalidineParcelPayloadProvider;
use SyliusYalidinePlugin\Resolver\YalidineShipmentResolver;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\EventDispatcher\GenericEvent;

final readonly class CreateYalidineShipmentOnOrderCompleteListener implements EventSubscriberInterface
{
    public function __construct(
        private YalidineClientInterface $yalidineClient,
        private YalidineParcelPayloadProvider $parcelPayloadProvider,
        private YalidineShipmentResolver $shipmentResolver,
        private RepositoryInterface $shipmentRepository,
        private LoggerInterface $logger,
    ) {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            'sylius.order.post_complete' => 'onOrderComplete',
        ];
    }

    public function onOrderComplete(GenericEvent $event): void
    {
        $subject = $event->getSubject();
        if (!$subject instanceof OrderInterface) {
            return;
        }

        $this->createForOrder($subject);
    }

    public function createForOrder(OrderInterface $order): void
    {
        foreach ($order->getShipments() as $shipment) {
            if (!$shipment instanceof ShipmentInterface) {
                continue;
            }

            if ($shipment->getTracking() !== null || !$this->shipmentResolver->isYalidineShipment($shipment)) {
                continue;
            }

            try {
                $payload = $this->parcelPayloadProvider->provide($order, $shipment);
                $response = $this->yalidineClient->createParcel($payload);

                // The API returns keyed by order_id when bulk-creating:
                // { "OrderNumber": { "success": true, "tracking": "yal-XXX", "label": "...", ... } }
                $orderNumber = (string) $order->getNumber();
                $parcelResult = $response[$orderNumber] ?? reset($response);

                if (!is_array($parcelResult)) {
                    throw new \RuntimeException('Yalidine API response has unexpected format.');
                }

                $success = (bool) ($parcelResult['success'] ?? false);
                if (!$success) {
                    $message = $parcelResult['message'] ?? 'Unknown error';
                    throw new \RuntimeException(sprintf('Yalidine parcel creation failed: %s', $message));
                }

                $tracking = $parcelResult['tracking'] ?? null;
                if (!is_string($tracking) || $tracking === '') {
                    throw new \RuntimeException('Yalidine API response did not return a valid tracking code.');
                }

                $shipment->setTracking($tracking);
                $this->shipmentRepository->add($shipment);

                $this->logger->info('Yalidine parcel created.', [
                    'tracking' => $tracking,
                    'order_number' => $orderNumber,
                    'label' => $parcelResult['label'] ?? null,
                ]);
            } catch (\Throwable $throwable) {
                $this->logger->error('Unable to create Yalidine parcel.', [
                    'exception' => $throwable,
                    'order_number' => $order->getNumber(),
                    'shipment_id' => $shipment->getId(),
                ]);
            }
        }
    }
}
