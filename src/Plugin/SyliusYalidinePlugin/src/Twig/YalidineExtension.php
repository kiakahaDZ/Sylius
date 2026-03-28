<?php

declare(strict_types=1);

namespace SyliusYalidinePlugin\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

final class YalidineExtension extends AbstractExtension
{
    /**
     * @param list<string> $shippingMethodCodes
     */
    public function __construct(private readonly array $shippingMethodCodes)
    {
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('yalidine_shipping_method_codes', $this->getShippingMethodCodes(...)),
        ];
    }

    /**
     * @return list<string>
     */
    public function getShippingMethodCodes(): array
    {
        return $this->shippingMethodCodes;
    }
}
