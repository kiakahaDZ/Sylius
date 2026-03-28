<?php

declare(strict_types=1);

namespace SyliusYalidinePlugin\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\Yaml\Yaml;

final class SyliusYalidineExtension extends Extension implements PrependExtensionInterface
{
    public function load(array $configs, ContainerBuilder $container): void
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new YamlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('services.yaml');

        $container->setParameter('sylius_yalidine.api_base_url', $config['api_base_url']);
        $container->setParameter('sylius_yalidine.api_id', $config['api_id']);
        $container->setParameter('sylius_yalidine.api_token', $config['api_token']);
        $container->setParameter('sylius_yalidine.webhook_token', $config['webhook_token']);
        $container->setParameter('sylius_yalidine.default_to_wilaya', $config['default_to_wilaya']);
        $container->setParameter('sylius_yalidine.default_from_wilaya', $config['default_from_wilaya']);
        $container->setParameter('sylius_yalidine.default_from_wilaya_id', (int) $config['default_from_wilaya']);
        $container->setParameter('sylius_yalidine.shipping_method_codes', $this->normalizeShippingMethodCodes($config['shipping_method_codes']));
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
        return 'sylius_yalidine';
    }

    /**
     * @param list<string> $shippingMethodCodes
     *
     * @return list<string>
     */
    private function normalizeShippingMethodCodes(array $shippingMethodCodes): array
    {
        $normalized = [];

        foreach ($shippingMethodCodes as $code) {
            foreach (explode(',', $code) as $splitCode) {
                $trimmedCode = trim($splitCode);
                if ($trimmedCode === '') {
                    continue;
                }

                $normalized[] = $trimmedCode;
            }
        }

        return array_values(array_unique($normalized));
    }
}
