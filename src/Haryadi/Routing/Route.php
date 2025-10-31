<?php
namespace Haryadi\Routing;

class Route
{
    public static function get(string $uri, $action)
    {
        Router::add('GET', $uri, $action);
    }

    public static function post(string $uri, $action)
    {
        Router::add('POST', $uri, $action);
    }

    public static function any(string $uri, $action)
    {
        Router::add('ANY', $uri, $action);
    }
}
