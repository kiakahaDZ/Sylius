<?php

declare(strict_types=1);

namespace SyliusRepairServicePlugin;

use Sylius\Bundle\CoreBundle\Application\SyliusPluginTrait;
use SyliusRepairServicePlugin\DependencyInjection\SyliusRepairServiceExtension;
use Symfony\Component\DependencyInjection\Extension\ExtensionInterface;
use Symfony\Component\HttpKernel\Bundle\Bundle;

final class SyliusRepairServicePlugin extends Bundle
{
    use SyliusPluginTrait;

    public function getContainerExtension(): ?ExtensionInterface
    {
        return new SyliusRepairServiceExtension();
    }
}
