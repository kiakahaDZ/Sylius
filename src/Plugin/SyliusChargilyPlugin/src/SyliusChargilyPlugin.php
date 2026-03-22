<?php

declare(strict_types=1);

namespace SyliusChargilyPlugin;

use Sylius\Bundle\CoreBundle\Application\SyliusPluginTrait;
use SyliusChargilyPlugin\DependencyInjection\SyliusChargilyExtension;
use Symfony\Component\DependencyInjection\Extension\ExtensionInterface;
use Symfony\Component\HttpKernel\Bundle\Bundle;

final class SyliusChargilyPlugin extends Bundle
{
    use SyliusPluginTrait;

    public function getContainerExtension(): ?ExtensionInterface
    {
        return new SyliusChargilyExtension();
    }
}
