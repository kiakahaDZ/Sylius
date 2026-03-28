<?php

declare(strict_types=1);

namespace SyliusYalidinePlugin\Controller\Shop;

use SyliusYalidinePlugin\Client\YalidineClientInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

final readonly class YalidineApiController
{
    public function __construct(
        private YalidineClientInterface $yalidineClient,
        private string $defaultFromWilaya,
    ) {
    }

    public function wilayas(): JsonResponse
    {
        try {
            $data = $this->yalidineClient->getWilayas();

            // Normalize field names for the JS controller
            $normalized = array_map(static fn (array $w) => [
                'wilaya_id' => $w['id'] ?? $w['wilaya_id'] ?? 0,
                'name' => $w['name'] ?? '',
                'is_deliverable' => $w['is_deliverable'] ?? true,
            ], $data);

            // Filter only deliverable wilayas and sort by ID
            $normalized = array_filter($normalized, static fn (array $w) => $w['is_deliverable']);
            usort($normalized, static fn (array $a, array $b) => $a['wilaya_id'] <=> $b['wilaya_id']);

            return new JsonResponse(array_values($normalized));
        } catch (\Throwable) {
            return new JsonResponse($this->getFallbackWilayas());
        }
    }

    public function communes(Request $request): JsonResponse
    {
        $wilayaId = (int) $request->query->get('wilaya_id', 0);
        if ($wilayaId <= 0) {
            return new JsonResponse([]);
        }

        try {
            $data = $this->yalidineClient->getCommunes($wilayaId);

            // Normalize for JS controller
            $normalized = array_map(static fn (array $c) => [
                'commune_id' => $c['id'] ?? $c['commune_id'] ?? 0,
                'name' => $c['name'] ?? '',
                'is_deliverable' => $c['is_deliverable'] ?? true,
                'has_stop_desk' => $c['has_stop_desk'] ?? false,
            ], $data);

            // Filter deliverable communes
            $normalized = array_filter($normalized, static fn (array $c) => $c['is_deliverable']);
            usort($normalized, static fn (array $a, array $b) => $a['name'] <=> $b['name']);

            return new JsonResponse(array_values($normalized));
        } catch (\Throwable) {
            return new JsonResponse([]);
        }
    }

    public function fees(Request $request): JsonResponse
    {
        $toWilayaId = (int) $request->query->get('to_wilaya_id', 0);
        if ($toWilayaId <= 0) {
            return new JsonResponse(['error' => 'Missing to_wilaya_id'], Response::HTTP_BAD_REQUEST);
        }

        $fromWilayaId = (int) $request->query->get('from_wilaya_id', (int) $this->defaultFromWilaya);
        $communeId = $request->query->get('commune_id', '');

        try {
            $data = $this->yalidineClient->getFees($fromWilayaId, $toWilayaId);

            $result = [
                'from_wilaya' => $data['from_wilaya_name'] ?? '',
                'to_wilaya' => $data['to_wilaya_name'] ?? '',
                'zone' => $data['zone'] ?? null,
                'retour_fee' => $data['retour_fee'] ?? null,
            ];

            // If a specific commune_id is requested, return its fee
            if ($communeId !== '' && isset($data['per_commune'][$communeId])) {
                $commune = $data['per_commune'][$communeId];
                $result['home_fee'] = $commune['express_home'] ?? null;
                $result['desk_fee'] = $commune['express_desk'] ?? null;
                $result['commune_name'] = $commune['commune_name'] ?? '';
            } elseif (isset($data['per_commune']) && is_array($data['per_commune'])) {
                // Return all commune fees for the JS to use
                $communes = [];
                foreach ($data['per_commune'] as $cId => $commune) {
                    $communes[] = [
                        'commune_id' => $commune['commune_id'] ?? $cId,
                        'commune_name' => $commune['commune_name'] ?? '',
                        'home_fee' => $commune['express_home'] ?? null,
                        'desk_fee' => $commune['express_desk'] ?? null,
                    ];
                }
                $result['communes'] = $communes;

                // Also set a "default" using first commune's fee
                $first = reset($data['per_commune']);
                if (is_array($first)) {
                    $result['home_fee'] = $first['express_home'] ?? null;
                    $result['desk_fee'] = $first['express_desk'] ?? null;
                }
            }

            return new JsonResponse($result);
        } catch (\Throwable) {
            return new JsonResponse(['error' => 'Failed to get fees'], Response::HTTP_BAD_GATEWAY);
        }
    }

    /**
     * Fallback list of Algeria's 58 wilayas if the Yalidine API is unavailable.
     *
     * @return list<array{wilaya_id: int, name: string}>
     */
    private function getFallbackWilayas(): array
    {
        return [
            ['wilaya_id' => 1,  'name' => 'Adrar'],
            ['wilaya_id' => 2,  'name' => 'Chlef'],
            ['wilaya_id' => 3,  'name' => 'Laghouat'],
            ['wilaya_id' => 4,  'name' => 'Oum El Bouaghi'],
            ['wilaya_id' => 5,  'name' => 'Batna'],
            ['wilaya_id' => 6,  'name' => 'Béjaïa'],
            ['wilaya_id' => 7,  'name' => 'Biskra'],
            ['wilaya_id' => 8,  'name' => 'Béchar'],
            ['wilaya_id' => 9,  'name' => 'Blida'],
            ['wilaya_id' => 10, 'name' => 'Bouira'],
            ['wilaya_id' => 11, 'name' => 'Tamanrasset'],
            ['wilaya_id' => 12, 'name' => 'Tébessa'],
            ['wilaya_id' => 13, 'name' => 'Tlemcen'],
            ['wilaya_id' => 14, 'name' => 'Tiaret'],
            ['wilaya_id' => 15, 'name' => 'Tizi Ouzou'],
            ['wilaya_id' => 16, 'name' => 'Alger'],
            ['wilaya_id' => 17, 'name' => 'Djelfa'],
            ['wilaya_id' => 18, 'name' => 'Jijel'],
            ['wilaya_id' => 19, 'name' => 'Sétif'],
            ['wilaya_id' => 20, 'name' => 'Saïda'],
            ['wilaya_id' => 21, 'name' => 'Skikda'],
            ['wilaya_id' => 22, 'name' => 'Sidi Bel Abbès'],
            ['wilaya_id' => 23, 'name' => 'Annaba'],
            ['wilaya_id' => 24, 'name' => 'Guelma'],
            ['wilaya_id' => 25, 'name' => 'Constantine'],
            ['wilaya_id' => 26, 'name' => 'Médéa'],
            ['wilaya_id' => 27, 'name' => 'Mostaganem'],
            ['wilaya_id' => 28, 'name' => "M'Sila"],
            ['wilaya_id' => 29, 'name' => 'Mascara'],
            ['wilaya_id' => 30, 'name' => 'Ouargla'],
            ['wilaya_id' => 31, 'name' => 'Oran'],
            ['wilaya_id' => 32, 'name' => 'El Bayadh'],
            ['wilaya_id' => 33, 'name' => 'Illizi'],
            ['wilaya_id' => 34, 'name' => 'Bordj Bou Arréridj'],
            ['wilaya_id' => 35, 'name' => 'Boumerdès'],
            ['wilaya_id' => 36, 'name' => 'El Tarf'],
            ['wilaya_id' => 37, 'name' => 'Tindouf'],
            ['wilaya_id' => 38, 'name' => 'Tissemsilt'],
            ['wilaya_id' => 39, 'name' => 'El Oued'],
            ['wilaya_id' => 40, 'name' => 'Khenchela'],
            ['wilaya_id' => 41, 'name' => 'Souk Ahras'],
            ['wilaya_id' => 42, 'name' => 'Tipaza'],
            ['wilaya_id' => 43, 'name' => 'Mila'],
            ['wilaya_id' => 44, 'name' => 'Aïn Defla'],
            ['wilaya_id' => 45, 'name' => 'Naama'],
            ['wilaya_id' => 46, 'name' => 'Aïn Témouchent'],
            ['wilaya_id' => 47, 'name' => 'Ghardaïa'],
            ['wilaya_id' => 48, 'name' => 'Relizane'],
            ['wilaya_id' => 49, 'name' => 'Timimoun'],
            ['wilaya_id' => 50, 'name' => 'Bordj Badji Mokhtar'],
            ['wilaya_id' => 51, 'name' => 'Ouled Djellal'],
            ['wilaya_id' => 52, 'name' => 'Béni Abbès'],
            ['wilaya_id' => 53, 'name' => 'In Salah'],
            ['wilaya_id' => 54, 'name' => 'In Guezzam'],
            ['wilaya_id' => 55, 'name' => 'Touggourt'],
            ['wilaya_id' => 56, 'name' => 'Djanet'],
            ['wilaya_id' => 57, 'name' => "El M'Ghair"],
            ['wilaya_id' => 58, 'name' => 'El Meniaa'],
        ];
    }
}
