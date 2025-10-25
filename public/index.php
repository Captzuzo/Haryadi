<?php
declare(strict_types=1);

// Define base path
define('BASE_PATH', dirname(__DIR__));
define('APP_PATH', BASE_PATH . '/app');
define('STORAGE_PATH', BASE_PATH . '/storage');
// Error reporting untuk development
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


// Load composer autoload
require_once BASE_PATH . '/vendor/autoload.php';

require_once BASE_PATH . '/core/Router.php';

// Load environment
Haryadi\Core\Environment::load(BASE_PATH);

// Create application instance
$app = new Haryadi\Core\App();

// Register routes
$app->router()->registerRoutes();


// // Dispatch request
// $router->dispatch();

// Handle request
$app->run();
