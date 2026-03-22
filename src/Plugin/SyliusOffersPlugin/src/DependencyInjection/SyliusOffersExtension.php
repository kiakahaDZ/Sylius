<?php

declare(strict_types = 1)
;

namespace SyliusOffersPlugin\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\Yaml\Yaml;

class SyliusOffersExtension extends Extension implements PrependExtensionInterface
{
    public function load(array $configs, ContainerBuilder $container): void
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new YamlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('services.yaml');

        $container->setParameter('sylius_offers.auto_track_views', $config['auto_track_views']);
        $container->setParameter('sylius_offers.cache_ttl', $config['cache_ttl']);
        $container->setParameter('sylius_offers.default_styles', $config['default_styles']);
    }

    public function prepend(ContainerBuilder $container): void
    {
        $config = Yaml::parseFile(__DIR__ . '/../Resources/config/app/config.yaml');

        foreach ($config as $name => $extensionConfig) {
            if ($name === 'sylius_offers') {
                continue;
            }
            $container->prependExtensionConfig($name, $extensionConfig);
        }
    }

    public function getAlias(): string
    {
        return 'sylius_offers';
    }
}