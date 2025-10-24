<?php
namespace Haryadi\Core;

class App
{
    private static $instance;
    private $router;
    private $database;

    public function __construct()
    {
        self::$instance = $this;
        $this->bootstrap();
    }

    public static function getInstance(): self
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function bootstrap(): void
    {
        // Initialize core components
        $this->router = new Router();
        
        // Database will be initialized only when needed (lazy loading)
        $this->database = null;
    }

    public function router(): Router
    {
        return $this->router;
    }

    public function database(): Database
    {
        if ($this->database === null) {
            $this->database = new Database();
        }
        return $this->database;
    }

    public function run(): void
    {
        $this->router->dispatch();
    }
}