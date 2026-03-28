<?php

declare(strict_types=1);

namespace SyliusYalidinePlugin\Provider;

use Sylius\Component\Core\Model\OrderInterface;
use Sylius\Component\Core\Model\ShipmentInterface;

final readonly class YalidineParcelPayloadProvider
{
    public function __construct(
        private string $defaultFromWilaya,
        private string $defaultToWilaya,
    ) {
    }

    /**
     * Build the payload for POST /v1/parcels/ (array of one parcel).
     *
     * @return list<array<string, mixed>>
     */
    public function provide(OrderInterface $order, ShipmentInterface $shipment): array
    {
        $shippingAddress = $order->getShippingAddress();
        if ($shippingAddress === null) {
            throw new \RuntimeException('Cannot create Yalidine parcel without shipping address.');
        }

        $firstName = trim((string) $shippingAddress->getFirstName());
        $lastName = trim((string) $shippingAddress->getLastName());

        $productList = [];
        foreach ($order->getItems() as $item) {
            $productList[] = sprintf('%s x%d', $item->getProductName(), $item->getQuantity());
        }

        // Wilaya: stored in provinceCode or provinceName
        $wilayaName = '';
        if ($shippingAddress->getProvinceName() !== null && $shippingAddress->getProvinceName() !== '') {
            $wilayaName = $shippingAddress->getProvinceName();
        } else {
            $wilayaCode = (string) ($shippingAddress->getProvinceCode() ?? $this->defaultToWilaya);
            if (str_contains($wilayaCode, '-')) {
                $wilayaCode = explode('-', $wilayaCode, 2)[1];
            }
            $wilayaName = $this->resolveWilayaName((int) $wilayaCode);
        }

        // Commune: the city field holds the commune name written by the JS controller.
        $communeName = trim((string) ($shippingAddress->getCity() ?? ''));

        // Calculate price in DZD (Sylius stores in cents)
        $priceInDzd = (int) round($order->getItemsTotal() / 100);

        // Delivery fee in DZD
        $deliveryFeeInDzd = (int) round($shipment->getAdjustmentsTotal() / 100);

        return [
            [
                'order_id' => (string) $order->getNumber(),
                'from_wilaya_name' => $this->resolveWilayaName((int) $this->defaultFromWilaya),
                'firstname' => $firstName ?: 'Client',
                'familyname' => $lastName ?: 'Sylius',
                'contact_phone' => (string) ($shippingAddress->getPhoneNumber() ?? '0000000000'),
                'address' => trim(sprintf('%s %s', (string) $shippingAddress->getStreet(), (string) $shippingAddress->getPostcode())),
                'to_commune_name' => $communeName,
                'to_wilaya_name' => $wilayaName,
                'product_list' => implode(', ', $productList),
                'price' => min($priceInDzd, 150000),
                'do_insurance' => false,
                'declared_value' => min($priceInDzd, 150000),
                'length' => 30,
                'width' => 20,
                'height' => 10,
                'weight' => 1,
                'freeshipping' => $deliveryFeeInDzd <= 0,
                'is_stopdesk' => false,
                'has_exchange' => false,
            ],
        ];
    }

    /**
     * Map wilaya ID to name. Full list of Algeria's 58 wilayas.
     */
    private function resolveWilayaName(int $wilayaId): string
    {
        $wilayas = [
            1 => 'Adrar', 2 => 'Chlef', 3 => 'Laghouat', 4 => 'Oum El Bouaghi',
            5 => 'Batna', 6 => 'Béjaïa', 7 => 'Biskra', 8 => 'Béchar',
            9 => 'Blida', 10 => 'Bouira', 11 => 'Tamanrasset', 12 => 'Tébessa',
            13 => 'Tlemcen', 14 => 'Tiaret', 15 => 'Tizi Ouzou', 16 => 'Alger',
            17 => 'Djelfa', 18 => 'Jijel', 19 => 'Sétif', 20 => 'Saïda',
            21 => 'Skikda', 22 => 'Sidi Bel Abbès', 23 => 'Annaba', 24 => 'Guelma',
            25 => 'Constantine', 26 => 'Médéa', 27 => 'Mostaganem', 28 => "M'Sila",
            29 => 'Mascara', 30 => 'Ouargla', 31 => 'Oran', 32 => 'El Bayadh',
            33 => 'Illizi', 34 => 'Bordj Bou Arréridj', 35 => 'Boumerdès', 36 => 'El Tarf',
            37 => 'Tindouf', 38 => 'Tissemsilt', 39 => 'El Oued', 40 => 'Khenchela',
            41 => 'Souk Ahras', 42 => 'Tipaza', 43 => 'Mila', 44 => 'Aïn Defla',
            45 => 'Naama', 46 => 'Aïn Témouchent', 47 => 'Ghardaïa', 48 => 'Relizane',
            49 => 'Timimoun', 50 => 'Bordj Badji Mokhtar', 51 => 'Ouled Djellal',
            52 => 'Béni Abbès', 53 => 'In Salah', 54 => 'In Guezzam',
            55 => 'Touggourt', 56 => 'Djanet', 57 => "El M'Ghair", 58 => 'El Meniaa',
        ];

        return $wilayas[$wilayaId] ?? 'Alger';
    }
}
