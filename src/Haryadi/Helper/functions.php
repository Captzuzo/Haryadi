<?php

if (!function_exists('view')) {
    function view(string $template, array $data = [])
    {
        $view = new \Haryadi\View\View();
        $view->render($template, $data);
    }
}

if (!function_exists('env')) {
    function env(string $key, $default = null)
    {
        return $_ENV[$key] ?? $default;
    }
}

if (!function_exists('dd')) {
    function dd(...$args)
    {
        foreach ($args as $x) {
            var_dump($x);
        }
        die;
    }
}

if (!function_exists('config')) {
    function config(string $key, $default = null)
    {
        $keys = explode('.', $key);
        $filename = array_shift($keys);
        $config = require dirname(dirname(dirname(__DIR__))) . "/config/{$filename}.php";
        
        foreach ($keys as $key) {
            if (!isset($config[$key])) {
                return $default;
            }
            $config = $config[$key];
        }
        
        return $config;
    }
}