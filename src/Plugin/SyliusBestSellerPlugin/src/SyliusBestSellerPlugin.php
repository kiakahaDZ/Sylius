<?php

declare(strict_types = 1)
;

namespace SyliusBestSellerPlugin;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Sylius\Bundle\CoreBundle\Application\SyliusPluginTrait;
use Symfony\Component\DependencyInjection\Extension\ExtensionInterface;

use SyliusBestSellerPlugin\DependencyInjection\SyliusBestSellerExtension;

final class SyliusBestSellerPlugin extends Bundle
{
    use SyliusPluginTrait;

    public function getContainerExtension(): ?ExtensionInterface
    {
        return new SyliusBestSellerExtension();
    }
}
