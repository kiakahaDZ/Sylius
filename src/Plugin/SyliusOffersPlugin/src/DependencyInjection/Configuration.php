<?php

declare(strict_types = 1)
;

namespace SyliusOffersPlugin\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('sylius_offers');
        $rootNode = $treeBuilder->getRootNode();

        $rootNode
            ->children()
            ->booleanNode('auto_track_views')
            ->defaultTrue()
            ->end()
            ->integerNode('cache_ttl')
            ->min(60)
            ->defaultValue(3600)
            ->end()
            ->arrayNode('default_styles')
            ->addDefaultsIfNotSet()
            ->children()
            ->scalarNode('card_background')
            ->defaultValue('#f8f9fa')
            ->end()
            ->scalarNode('card_text')
            ->defaultValue('#212529')
            ->end()
            ->scalarNode('banner_gradient')
            ->defaultValue('linear-gradient(135deg, #667eea 0%, #764ba2 100%)')
            ->end()
            ->scalarNode('button_color')
            ->defaultValue('#007bff')
            ->end()
            ->end()
            ->end()
            ->end();

        return $treeBuilder;
    }
}