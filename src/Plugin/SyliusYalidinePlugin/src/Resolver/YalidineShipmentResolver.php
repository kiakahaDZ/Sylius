<?php

declare(strict_types=1);

namespace SyliusYalidinePlugin\Resolver;

use Sylius\Component\Core\Model\ShipmentInterface;

final readonly class YalidineShipmentResolver
{
    /**
     * @param list<string> $shippingMethodCodes
     */
    public function __construct(private array $shippingMethodCodes)
    {
    }

    public function isYalidineShipment(ShipmentInterface $shipment): bool
    {
        $method = $shipment->getMethod();
        if ($method === null) {
            return false;
        }

        return in_array($method->getCode(), $this->shippingMethodCodes, true);
    }
}
