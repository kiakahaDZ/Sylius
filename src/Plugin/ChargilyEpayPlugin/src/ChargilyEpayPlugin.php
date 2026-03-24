<?php

declare(strict_types = 1)
;

namespace ChargilyEpayPlugin;

use ChargilyEpayPlugin\DependencyInjection\KiakahaChargilyExtension;
use Sylius\Bundle\CoreBundle\Application\SyliusPluginTrait;
use Symfony\Component\DependencyInjection\Extension\ExtensionInterface;
use Symfony\Component\HttpKernel\Bundle\Bundle;

final class ChargilyEpayPlugin extends Bundle
{
    use SyliusPluginTrait;

    public function getContainerExtension(): ?ExtensionInterface
    {
        return new KiakahaChargilyExtension();
    }
}
