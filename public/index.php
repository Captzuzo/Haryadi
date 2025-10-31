<?php

require __DIR__ . '/../vendor/autoload.php';

// Load .env if present
if (file_exists(__DIR__ . '/../.env')) {
    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/..');
    $dotenv->load();
} else {
    // Create default .env if it doesn't exist
    copy(__DIR__ . '/../.env.example', __DIR__ . '/../.env');
    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/..');
    $dotenv->load();
}

error_reporting(E_ALL);
ini_set('display_errors', '1');

use Haryadi\Application;
use Haryadi\Http\Middleware\CsrfMiddleware;

$app = Application::getInstance(dirname(__DIR__));
$app->middleware(CsrfMiddleware::class);

$kernel = new \Haryadi\Kernel($app);
$kernel->boot();

$request = $app->getRequest();
$response = $kernel->handle($request);

if (method_exists($response, 'send')) {
    $response->send();
} else {
    echo (string) $response;
}
