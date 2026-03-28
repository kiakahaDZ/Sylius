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
            $status = (string) ($parcel['last_status'] ?? $parcel['status'] ?? 'unknown');
            $this->addFlash('success', 'sylius_yalidine.admin.status_synced');
            $this->addFlash('info', sprintf('Yalidine: %s', $status));

            // If delivered, update Sylius shipment state
            if (in_array($status, ['Livré', 'delivered'], true)) {
                $shipment->setState(ShipmentInterface::STATE_SHIPPED);
                $this->shipmentRepository->add($shipment);
            }
        } catch (\Throwable) {
            $this->addFlash('error', 'sylius_yalidine.admin.sync_failed');
        }

        return $this->redirectToRoute('sylius_admin_shipment_show', ['id' => $id]);
    }

    /**
     * Redirect to the official Yalidine bordereau page.
     */
    public function printTicketAction(int $id): Response
    {
        $shipment = $this->getShipment($id);
        if ($shipment === null || !$this->shipmentResolver->isYalidineShipment($shipment) || $shipment->getTracking() === null) {
            throw $this->createNotFoundException('Shipment cannot be printed using Yalidine ticket.');
        }

        $tracking = $shipment->getTracking();

        // Try to get the label URL from the API
        try {
            $parcel = $this->yalidineClient->getParcel($tracking);
            $labelUrl = $parcel['label'] ?? null;

            if (is_string($labelUrl) && str_starts_with($labelUrl, 'http')) {
                return $this->redirect($labelUrl);
            }
        } catch (\Throwable) {
            // Fall through to default URL
        }

        // Fallback: construct the bordereau URL directly
        $bordereauUrl = sprintf('https://yalidine.app/app/bordereau.php?tracking=%s', urlencode($tracking));

        return $this->redirect($bordereauUrl);
    }

    /**
     * View parcel details from Yalidine API.
     */
    public function viewParcelAction(int $id): Response
    {
        $shipment = $this->getShipment($id);
        if ($shipment === null || !$this->shipmentResolver->isYalidineShipment($shipment) || $shipment->getTracking() === null) {
            throw $this->createNotFoundException('Not a Yalidine shipment.');
        }

        try {
            $parcel = $this->yalidineClient->getParcel($shipment->getTracking());
        } catch (\Throwable) {
            $parcel = [];
        }

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
