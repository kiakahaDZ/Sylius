<?php

declare(strict_types = 1)
;

namespace SyliusBestSellerPlugin\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('sylius_best_seller');
        $rootNode = $treeBuilder->getRootNode();

        $rootNode
            ->children()
            ->booleanNode('display_on_homepage')
            ->defaultTrue()
            ->end()
            ->integerNode('number_of_products')
            ->min(1)
            ->max(50)
            ->defaultValue(8)
            ->end()
            ->enumNode('period')
            ->values(['daily', 'weekly', 'monthly', 'yearly', 'all_time'])
            ->defaultValue('weekly')
            ->end()
            ->enumNode('sort_by')
            ->values(['total_sales', 'total_quantity', 'total_revenue'])
            ->defaultValue('total_sales')
            ->end()
            ->integerNode('cache_ttl')
            ->min(60)
            ->defaultValue(3600)
            ->end()
            ->end();

        return $treeBuilder;
    }
}