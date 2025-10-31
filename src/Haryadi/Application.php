<?php
namespace Haryadi;

class Application
{
    private static $instance = null;
    protected string $basePath;
    private $router;
    private $request;
    private $session;
    private $globalMiddleware = [];

    public function __construct(string $basePath)
    {
        $this->basePath = rtrim($basePath, "\/\\");
        $this->router = new Routing\Router();
        $this->request = new Http\Request();
        $this->session = new Http\Session();
    }

    public static function getInstance($basePath = null) {
        if (self::$instance === null) {
            if ($basePath === null) {
                throw new \RuntimeException('Application must be initialized with a base path');
            }
            self::$instance = new self($basePath);
        }
        return self::$instance;
    }

    public function basePath(string $path = ''): string
    {
        return $this->basePath . ($path ? DIRECTORY_SEPARATOR . ltrim($path, "\/\\") : '');
    }

    public function getRouter() {
        return $this->router;
    }

    public function getRequest() {
        return $this->request;
    }

    public function getSession() {
        return $this->session;
    }

    public function middleware($middleware) {
        $this->globalMiddleware[] = new $middleware();
        return $this;
    }

    public function run() {
        session_start();
        $this->router->dispatch($this->request);
    }
}
