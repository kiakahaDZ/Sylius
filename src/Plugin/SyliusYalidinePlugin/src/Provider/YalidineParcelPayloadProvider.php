<?php

declare(strict_types=1);

namespace SyliusYalidinePlugin\Provider;

use Sylius\Component\Core\Model\OrderInterface;
use Sylius\Component\Core\Model\ShipmentInterface;

final readonly class YalidineParcelPayloadProvider
{
    public function __construct(private string $defaultToWilaya)
    {
    }

    /**
     * @return array<string, mixed>
     */
    public function provide(OrderInterface $order, ShipmentInterface $shipment): array
    {
        $shippingAddress = $order->getShippingAddress();
        if ($shippingAddress === null) {
            throw new \RuntimeException('Cannot create Yalidine parcel without shipping address.');
        }

        $name = trim(sprintf('%s %s', (string) $shippingAddress->getFirstName(), (string) $shippingAddress->getLastName()));
        $productList = [];

        foreach ($order->getItems() as $item) {
            $productList[] = sprintf('%s x%d', $item->getProductName(), $item->getQuantity());
        }

        return [
            'delivery_fee' => $shipment->getAdjustmentsTotal(),
            'has_exchange' => false,
            'note' => $order->getNotes(),
            'order_id' => (string) $order->getNumber(),
            'price' => $order->getItemsTotal(),
            'product_list' => implode(', ', $productList),
            'stopdesk_id' => null,
            'to_address' => trim(sprintf('%s %s', (string) $shippingAddress->getStreet(), (string) $shippingAddress->getPostcode())),
            'to_commune' => (string) ($shippingAddress->getCity() ?? ''),
            'to_name' => $name,
            'to_phone' => (string) ($shippingAddress->getPhoneNumber() ?? ''),
            'to_wilaya' => $this->defaultToWilaya,
        ];
    }
}
