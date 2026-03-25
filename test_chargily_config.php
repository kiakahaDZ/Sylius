<?php
require __DIR__ . '/vendor/autoload.php';

use Sylius\Component\Payment\Model\GatewayConfig;

// Boot Symfony
$kernel = new \App\Kernel('dev', true);
$kernel->boot();
$container = $kernel->getContainer();

$em = $container->get('doctrine')->getManager();
$gatewayConfig = $em->getRepository(GatewayConfig::class)->findOneBy(['factoryName' => 'chargily_pay']);

var_dump($gatewayConfig->getConfig());
