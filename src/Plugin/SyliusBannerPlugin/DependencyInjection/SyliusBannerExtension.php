<?php

declare(strict_types=1);

namespace Plugin\SyliusBannerPlugin\DependencyInjection;

use Sylius\Bundle\ResourceBundle\DependencyInjection\Extension\AbstractResourceExtension;
use Symfony\Component\Config\Definition\ConfigurationInterface;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;


final class SyliusBannerExtension extends AbstractResourceExtension implements PrependExtensionInterface
{
    public function prepend(ContainerBuilder $container): void
    {
        $config = $this->processConfiguration($this->getConfiguration([], $container), $container->getExtensionConfig($this->getAlias()));

        $resources = [];
        foreach ($config['resources'] as $name => $resourceConfig) {
            $resources['sylius_banner.' . $name] = $resourceConfig;
        }

        $container->prependExtensionConfig('sylius_resource', [
            'resources' => $resources,
        ]);
    }
    public function load(array $configs, ContainerBuilder $container): void
    {
        $config = $this->processConfiguration($this->getConfiguration([], $container), $configs);
        $loader = new YamlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));

        foreach ($config['resources'] as $resourceName => $resourceConfig) {
            foreach ($resourceConfig['classes'] as $className => $classValue) {
                $container->setParameter(sprintf('sylius_banner.model.%s.%s', $resourceName, $className), $classValue);
            }
        }

        $loader->load('services.yaml');
    }

    public function getAlias(): string
    {
        return 'sylius_banner';
    }

    public function getConfiguration(array $config, ContainerBuilder $container): ConfigurationInterface
    {
        return new Configuration();
    }
}
