<?php

use Dotenv\Dotenv;
use Haryadi\Routing\Route;

// Check PHP version
if (version_compare(PHP_VERSION, '8.0.0', '<')) {
    die('Haryadi Framework requires PHP 8.0 or higher. Current version: ' . PHP_VERSION);
}

// Load Composer's autoloader
if (!file_exists(__DIR__ . '/../vendor/autoload.php')) {
    die('Autoloader not found. Please run: composer install');
}
require_once __DIR__ . '/../vendor/autoload.php';

// Load environment variables
if (!file_exists(__DIR__ . '/../.env')) {
    die('.env file not found. Please copy .env.example to .env and configure your settings.');
}
$dotenv = Dotenv::createImmutable(dirname(__DIR__));
$dotenv->load();

// Set error reporting based on environment
if (env('APP_ENV') === 'production') {
    error_reporting(E_ALL & ~E_DEPRECATED & ~E_STRICT);
    ini_set('display_errors', '0');
} else {
    error_reporting(E_ALL);
    ini_set('display_errors', '1');
}

// Register error handler
set_error_handler(function ($severity, $message, $file, $line) {
    if (!(error_reporting() & $severity)) {
        return;
    }
    throw new \ErrorException($message, 0, $severity, $file, $line);
});

// Handle uncaught exceptions
set_exception_handler(function ($e) {
    $status = $e->getCode() ?: 500;
    if (!is_int($status) || $status < 400 || $status > 599) {
        $status = 500;
    }
    
    http_response_code($status);
    header('Content-Type: text/html; charset=UTF-8');
    
    if (env('APP_DEBUG', false)) {
        echo sprintf(
            "<h1>Error (%d): %s</h1>\n<p>File: %s:%d</p>\n<pre>%s</pre>",
            $status,
            htmlspecialchars($e->getMessage()),
            htmlspecialchars($e->getFile()),
            $e->getLine(),
            htmlspecialchars($e->getTraceAsString())
        );
    } else {
        $messages = [
            404 => 'Page Not Found',
            403 => 'Forbidden',
            401 => 'Unauthorized',
            500 => 'Internal Server Error',
            503 => 'Service Unavailable'
        ];
        echo "<h1>" . ($messages[$status] ?? 'An error occurred') . "</h1>";
    }
    exit(1);
});

// Boot the application
require_once __DIR__ . '/../routes/web.php';

// Handle the request
try {
    Route::dispatch();
} catch (\Exception $e) {
    throw $e;
}