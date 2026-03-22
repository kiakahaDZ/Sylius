<?php

declare(strict_types=1);

namespace SyliusChargilyPlugin\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\Yaml\Yaml;

final class SyliusChargilyExtension extends Extension implements PrependExtensionInterface
{
    public function load(array $configs, ContainerBuilder $container): void
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new YamlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('services.yaml');

        $container->setParameter('sylius_chargily.gateway_factory', $config['gateway_factory']);
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
        return 'sylius_chargily';
    }
}
