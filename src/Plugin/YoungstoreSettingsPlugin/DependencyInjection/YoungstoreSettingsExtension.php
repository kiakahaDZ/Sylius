<?php
namespace YoungstoreSettingsPlugin\DependencyInjection;

use Sylius\Bundle\ResourceBundle\DependencyInjection\Extension\AbstractResourceExtension;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

final class YoungstoreSettingsExtension extends AbstractResourceExtension implements PrependExtensionInterface
{
    public function prepend(ContainerBuilder $container): void
    {
        $config = $this->processConfiguration($this->getConfiguration([], $container), $container->getExtensionConfig($this->getAlias()));
        
        $resources = [];
        foreach ($config['resources'] as $name => $resourceConfig) {
            $resources['youngstore_settings.' . $name] = $resourceConfig;
        }

        $container->prependExtensionConfig('sylius_resource', [
            'resources' => $resources,
        ]);
    }

    public function load(array $configs, ContainerBuilder $container): void
    {
        $config = $this->processConfiguration($this->getConfiguration([], $container), $configs);
        $loader = new YamlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));

        $this->registerResources('youngstore_settings', $config['driver'], $config['resources'], $container);

        $loader->load('services.yaml');
    }

    public function getAlias(): string
    {
        return 'youngstore_settings';
    }
}
