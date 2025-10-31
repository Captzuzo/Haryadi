<?php
namespace Haryadi\Routing;

use Haryadi\Http\Request;
use Haryadi\Http\Response;

class Router
{
    protected static array $routes = [];

    public static function add(string $method, string $uri, $action): void
    {
        self::$routes[] = [
            'method' => strtoupper($method),
            'uri' => $uri,
            'action' => $action,
        ];
    }

    public static function dispatch(Request $request)
    {
        $path = $request->path();
        $method = $request->method();

        foreach (self::$routes as $route) {
            if (($route['method'] === 'ANY' || $route['method'] === $method) && $route['uri'] === $path) {
                $action = $route['action'];

                if (is_callable($action)) {
                    return call_user_func($action, $request);
                }

                if (is_array($action) && count($action) === 2) {
                    [$class, $methodName] = $action;
                    if (class_exists($class)) {
                        $controller = new $class();
                        return call_user_func([$controller, $methodName], $request);
                    }
                }
            }
        }

        return new Response('404 Not Found', 404);
    }
}
