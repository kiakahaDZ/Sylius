<?php

declare(strict_types=1);

namespace SyliusYalidinePlugin\Calculator;

use Psr\Log\LoggerInterface;
use Sylius\Component\Core\Model\ShipmentInterface as CoreShipmentInterface;
use Sylius\Component\Shipping\Calculator\CalculatorInterface;
use Sylius\Component\Shipping\Model\ShipmentInterface;
use SyliusYalidinePlugin\Client\YalidineClientInterface;

/**
 * Yalidine shipping calculator.
 *
 * Behaviour:
 * - If the admin set a fixed amount > 0 in the shipping method configuration, use that.
 * - Otherwise, call the Yalidine /fees API using the order shipping address wilaya + commune
 *   to get the real delivery fee.
 */
final class YalidineCalculator implements CalculatorInterface
{
    public function __construct(
        private readonly YalidineClientInterface $yalidineClient,
        private readonly int $defaultFromWilayaId,
        private readonly LoggerInterface $logger,
    ) {
    }

    /** @param array<string, mixed> $configuration */
    public function calculate(ShipmentInterface $subject, array $configuration): int
    {
        // If admin set a fixed amount, use it
        $fixedAmount = (int) ($configuration['amount'] ?? 0);
        if ($fixedAmount > 0) {
            return $fixedAmount;
        }

        // Dynamic: fetch from Yalidine API
        if (!$subject instanceof CoreShipmentInterface) {
            return 0;
        }

        $order = $subject->getOrder();
        if ($order === null) {
            return 0;
        }

        $address = $order->getShippingAddress();
        if ($address === null) {
            return 0;
        }

        // Only use Yalidine fees for Algeria
        if ($address->getCountryCode() !== 'DZ') {
            return $fixedAmount;
        }

        // Get wilaya ID from province code (e.g. "DZ-16" or "16")
        $provinceCode = (string) ($address->getProvinceCode() ?? $address->getProvinceName() ?? '');
        if (str_contains($provinceCode, '-')) {
            $provinceCode = explode('-', $provinceCode, 2)[1];
        }

        $toWilayaId = (int) $provinceCode;
        if ($toWilayaId <= 0) {
            return $fixedAmount;
        }

        try {
            $feesData = $this->yalidineClient->getFees($this->defaultFromWilayaId, $toWilayaId);

            // Get commune name from city field
            $communeName = trim((string) ($address->getCity() ?? ''));

            // The fees response has per_commune data keyed by commune_id
            if (isset($feesData['per_commune']) && is_array($feesData['per_commune'])) {
                // Try to find matching commune
                foreach ($feesData['per_commune'] as $communeData) {
                    if (
                        isset($communeData['commune_name']) &&
                        mb_strtolower($communeData['commune_name']) === mb_strtolower($communeName)
                    ) {
                        // Use express_home fee by default, fallback to express_desk
                        $fee = $communeData['express_home'] ?? $communeData['express_desk'] ?? null;
                        if ($fee !== null) {
                            // Convert to Sylius cents (Yalidine returns DZD as integer)
                            return (int) $fee * 100;
                        }

                        break;
                    }
                }

                // If commune not found, use first commune's fee as fallback
                $firstCommune = reset($feesData['per_commune']);
                if (is_array($firstCommune)) {
                    $fee = $firstCommune['express_home'] ?? $firstCommune['express_desk'] ?? 0;

                    return (int) $fee * 100;
                }
            }
        } catch (\Throwable $e) {
            $this->logger->warning('Yalidine fee calculation failed, falling back to fixed amount.', [
                'exception' => $e->getMessage(),
                'to_wilaya_id' => $toWilayaId,
            ]);
        }

        return $fixedAmount;
    }

    public function getType(): string
    {
        return 'yalidine';
    }
}
