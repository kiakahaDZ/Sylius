<?php

declare(strict_types=1);

namespace SyliusYalidinePlugin\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

final class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('sylius_yalidine');

        $treeBuilder
            ->getRootNode()
                ->addDefaultsIfNotSet()
                ->children()
                    ->scalarNode('api_base_url')->defaultValue('%env(YALIDINE_API_BASE_URL)%')->end()
                    ->scalarNode('api_id')->defaultValue('%env(YALIDINE_API_ID)%')->end()
                    ->scalarNode('api_token')->defaultValue('%env(YALIDINE_API_TOKEN)%')->end()
                    ->scalarNode('webhook_token')->defaultValue('%env(YALIDINE_WEBHOOK_TOKEN)%')->end()
                    ->scalarNode('default_to_wilaya')->defaultValue('%env(YALIDINE_DEFAULT_TO_WILAYA)%')->end()
                    ->arrayNode('shipping_method_codes')
                        ->scalarPrototype()->end()
                        ->defaultValue(['%env(default:yalidine:YALIDINE_SHIPPING_METHOD_CODES)%'])
                    ->end()
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
