<?php

declare(strict_types=1);

namespace SyliusYalidinePlugin\Controller\Admin;

use Sylius\Component\Core\Model\ShipmentInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;
use SyliusYalidinePlugin\Client\YalidineClientInterface;
use SyliusYalidinePlugin\Resolver\YalidineShipmentResolver;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;

final class YalidineShipmentController extends AbstractController
{
    public function __construct(
        private readonly RepositoryInterface $shipmentRepository,
        private readonly YalidineClientInterface $yalidineClient,
        private readonly YalidineShipmentResolver $shipmentResolver,
    ) {
    }

    public function syncStatusAction(int $id): RedirectResponse
    {
        $shipment = $this->getShipment($id);
        if ($shipment === null || !$this->shipmentResolver->isYalidineShipment($shipment) || $shipment->getTracking() === null) {
            $this->addFlash('error', 'sylius_yalidine.admin.not_yalidine_shipment');

            return $this->redirectToRoute('sylius_admin_shipment_show', ['id' => $id]);
        }

        try {
            $parcel = $this->yalidineClient->getParcel($shipment->getTracking());
            $status = (string) ($parcel['status'] ?? $parcel['last_status'] ?? 'unknown');
            $this->addFlash('success', 'sylius_yalidine.admin.status_synced');
            $this->addFlash('info', sprintf('Yalidine status: %s', $status));
        } catch (\Throwable) {
            $this->addFlash('error', 'sylius_yalidine.admin.sync_failed');
        }

        return $this->redirectToRoute('sylius_admin_shipment_show', ['id' => $id]);
    }

    public function printTicketAction(int $id): Response
    {
        $shipment = $this->getShipment($id);
        if ($shipment === null || !$this->shipmentResolver->isYalidineShipment($shipment) || $shipment->getTracking() === null) {
            throw $this->createNotFoundException('Shipment cannot be printed using Yalidine ticket.');
        }

        $parcel = $this->yalidineClient->getParcel($shipment->getTracking());

        return $this->render('@SyliusYalidinePlugin/admin/shipment/ticket.html.twig', [
            'parcel' => $parcel,
            'shipment' => $shipment,
        ]);
    }

    private function getShipment(int $id): ?ShipmentInterface
    {
        $shipment = $this->shipmentRepository->find($id);

        return $shipment instanceof ShipmentInterface ? $shipment : null;
    }
}
