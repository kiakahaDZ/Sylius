<?php

declare(strict_types=1);

namespace Plugin\SyliusBannerPlugin;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Sylius\Bundle\CoreBundle\Application\SyliusPluginTrait;
use Symfony\Component\DependencyInjection\Extension\ExtensionInterface;
use Plugin\SyliusBannerPlugin\DependencyInjection\SyliusBannerExtension;

final class SyliusBannerPlugin extends Bundle
{
    use SyliusPluginTrait;

    public function getContainerExtension(): ?ExtensionInterface
    {
        return new SyliusBannerExtension();
    }
}
