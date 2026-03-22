<?php
require 'vendor/autoload.php';

use Sylius\Bundle\ThemeBundle\Configuration\Filesystem\FilesystemConfigurationProvider;
use Sylius\Bundle\ThemeBundle\Configuration\Filesystem\ProcessingConfigurationLoader;
use Sylius\Bundle\ThemeBundle\Configuration\Filesystem\JsonFileConfigurationLoader;
use Sylius\Bundle\ThemeBundle\Configuration\SymfonyConfigurationProcessor;
use Sylius\Bundle\ThemeBundle\Configuration\ThemeConfiguration;
use Sylius\Bundle\ThemeBundle\Filesystem\Filesystem;
use Sylius\Bundle\ThemeBundle\Locator\RecursiveFileLocator;
use Sylius\Bundle\ThemeBundle\Factory\FinderFactory;
use Symfony\Component\Config\Definition\Processor;

$dir = __DIR__ . '/themes';

echo "Step 1: Create RecursiveFileLocator\n";
$finderFactory = new FinderFactory();
$fileLocator = new RecursiveFileLocator($finderFactory, [$dir], 1);

echo "Step 2: Locate files\n";
try {
    $files = $fileLocator->locateFilesNamed('composer.json');
    echo "  Found " . count($files) . " file(s):\n";
    foreach ($files as $file) {
        echo "    - $file\n";
    }
} catch (\Exception $e) {
    echo "  Error: " . $e->getMessage() . "\n";
}

echo "\nStep 3: Create configuration loader\n";
$filesystem = new Filesystem();
$jsonLoader = new JsonFileConfigurationLoader($filesystem);
$processor = new SymfonyConfigurationProcessor(new ThemeConfiguration(), new Processor());
$loader = new ProcessingConfigurationLoader($jsonLoader, $processor);

echo "Step 4: Load configuration from each file\n";
foreach ($files as $file) {
    try {
        $config = $loader->load($file);
        echo "  Config for $file:\n";
        print_r($config);
    } catch (\Exception $e) {
        echo "  Error loading $file: " . $e->getMessage() . "\n";
        echo "  Trace: " . $e->getTraceAsString() . "\n";
    }
}

echo "\nStep 5: Create FilesystemConfigurationProvider\n";
$provider = new FilesystemConfigurationProvider($fileLocator, $loader, 'composer.json');
try {
    $configs = $provider->getConfigurations();
    echo "  Configurations: " . count($configs) . "\n";
    foreach ($configs as $config) {
        echo "  Theme: " . ($config['name'] ?? 'N/A') . "\n";
    }
} catch (\Exception $e) {
    echo "  Error: " . $e->getMessage() . "\n";
}
