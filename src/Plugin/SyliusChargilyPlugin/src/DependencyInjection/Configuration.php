<?php

declare(strict_types=1);

namespace SyliusChargilyPlugin\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

final class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('sylius_chargily');

        $treeBuilder
            ->getRootNode()
                ->addDefaultsIfNotSet()
                ->children()
                    ->scalarNode('gateway_factory')
                        ->defaultValue('chargily_pay')
                        ->cannotBeEmpty()
                    ->end()
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
