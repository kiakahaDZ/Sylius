<?php

declare(strict_types = 1);

namespace SyliusOffersPlugin;

use Sylius\Bundle\CoreBundle\Application\SyliusPluginTrait;
use SyliusOffersPlugin\DependencyInjection\SyliusOffersExtension;
use Symfony\Component\DependencyInjection\Extension\ExtensionInterface;
use Symfony\Component\HttpKernel\Bundle\Bundle;

final class SyliusOffersPlugin extends Bundle
{
    use SyliusPluginTrait;

    public function getContainerExtension(): ?ExtensionInterface
    {
        return new SyliusOffersExtension();
    }
}