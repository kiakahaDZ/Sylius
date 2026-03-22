<?php

declare(strict_types=1);

namespace SyliusRepairServicePlugin\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

final class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('sylius_repair_service');

        $treeBuilder
            ->getRootNode()
                ->addDefaultsIfNotSet()
            ->end()
        ;

        return $treeBuilder;
    }
}
