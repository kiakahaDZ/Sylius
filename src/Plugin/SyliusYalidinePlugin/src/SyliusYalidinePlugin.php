<?php

declare(strict_types=1);

namespace SyliusYalidinePlugin;

use Sylius\Bundle\CoreBundle\Application\SyliusPluginTrait;
use SyliusYalidinePlugin\DependencyInjection\SyliusYalidineExtension;
use Symfony\Component\DependencyInjection\Extension\ExtensionInterface;
use Symfony\Component\HttpKernel\Bundle\Bundle;

final class SyliusYalidinePlugin extends Bundle
{
    use SyliusPluginTrait;

    public function getContainerExtension(): ?ExtensionInterface
    {
        return new SyliusYalidineExtension();
    }
}
