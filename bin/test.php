<?php
use Sylius\Component\Payment\Model\GatewayConfig;

require dirname(__DIR__).'/vendor/autoload.php';
require dirname(__DIR__).'/config/bootstrap.php';

$kernel = new App\Kernel($_SERVER['APP_ENV'] ?? 'dev', (bool) ($_SERVER['APP_DEBUG'] ?? true));
$kernel->boot();

$em = $kernel->getContainer()->get('doctrine')->getManager();
$gatewayConfig = $em->getRepository(GatewayConfig::class)->findOneBy(['factoryName' => 'chargily_pay']);

var_dump($gatewayConfig->getConfig());
