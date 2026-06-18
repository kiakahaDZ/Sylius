<?php
require 'vendor/autoload.php';
(new \Symfony\Component\Dotenv\Dotenv())->bootEnv('.env');
echo "ENV: " . ($_ENV['DATABASE_URL'] ?? 'NOT SET') . "\n";
echo "SERVER: " . ($_SERVER['DATABASE_URL'] ?? 'NOT SET') . "\n";
echo "GETENV: " . (getenv('DATABASE_URL') ?: 'NOT SET') . "\n";
