<?php

namespace Haryadi\Routing;

class Route
{
    protected static array $routes = [];
    protected static array $patterns = [
        ':any' => '([^/]+)',
        ':id' => '(\d+)',
        ':string' => '([a-zA-Z]+)',
        ':number' => '(\d+)',
        ':alpha' => '([a-zA-Z0-9-]+)'
    ];

    public static function get(string $uri, array $handler): void
    {
        self::addRoute('GET', $uri, $handler);
    }

    public static function post(string $uri, array $handler): void
    {
        self::addRoute('POST', $uri, $handler);
    }

    public static function put(string $uri, array $handler): void
    {
        self::addRoute('PUT', $uri, $handler);
    }

    public static function delete(string $uri, array $handler): void
    {
        self::addRoute('DELETE', $uri, $handler);
    }

    protected static function addRoute(string $method, string $uri, array $handler): void
    {
        $uri = trim($uri, '/');
        self::$routes[$method][$uri] = $handler;
    }

    public static function dispatch(): void
    {
        $uri = $_SERVER['REQUEST_URI'];
        $uri = trim(parse_url($uri, PHP_URL_PATH), '/');
        $method = $_SERVER['REQUEST_METHOD'];

        foreach (self::$routes[$method] ?? [] as $route => $handler) {
            $pattern = self::convertPattern($route);
            if (preg_match("#^{$pattern}$#", $uri, $matches)) {
                array_shift($matches);
                self::executeHandler($handler, $matches);
                return;
            }
        }

        throw new \Exception("Route not found: {$uri}", 404);
    }

    protected static function convertPattern(string $route): string
    {
        if (strpos($route, ':') !== false) {
            foreach (self::$patterns as $key => $pattern) {
                $route = str_replace($key, $pattern, $route);
            }
        }
        return $route;
    }

    protected static function executeHandler(array $handler, array $params = []): void
    {
        [$class, $method] = $handler;
        $controller = new $class();
        call_user_func_array([$controller, $method], $params);
    }
}