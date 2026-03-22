<?php

declare(strict_types = 1);

namespace SyliusBestSellerPlugin\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\Yaml\Yaml;

class SyliusBestSellerExtension extends Extension implements PrependExtensionInterface
{
    public function load(array $configs, ContainerBuilder $container): void
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new YamlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('services.yaml');

        // Set parameters
        $container->setParameter('sylius_best_seller.display_on_homepage', $config['display_on_homepage']);
        $container->setParameter('sylius_best_seller.number_of_products', $config['number_of_products']);
        $container->setParameter('sylius_best_seller.period', $config['period']);
        $container->setParameter('sylius_best_seller.sort_by', $config['sort_by']);
        $container->setParameter('sylius_best_seller.cache_ttl', $config['cache_ttl']);
    }

    public function prepend(ContainerBuilder $container): void
    {
        $config = Yaml::parseFile(__DIR__ . '/../Resources/config/app/config.yaml');
        
        foreach ($config as $name => $extensionConfig) {
            $container->prependExtensionConfig($name, $extensionConfig);
        }
    }

    public function getAlias(): string
    {
        return 'sylius_best_seller';
    }
}