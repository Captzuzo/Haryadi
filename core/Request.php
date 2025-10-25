<?php
namespace Haryadi\Core;

class Request
{
    public function getMethod(): string
    {
        return $_SERVER['REQUEST_METHOD'] ?? 'GET';
    }

    public function getPath(): string
    {
        $path = $_SERVER['REQUEST_URI'] ?? '/';
        $position = strpos($path, '?');
        
        if ($position === false) {
            return $path;
        }
        
        return substr($path, 0, $position);
    }

    public function get(string $key = null, $default = null)
    {
        if ($key === null) {
            return $_GET;
        }
        
        return $_GET[$key] ?? $default;
    }

    public function post(string $key = null, $default = null)
    {
        if ($key === null) {
            return $_POST;
        }
        
        return $_POST[$key] ?? $default;
    }

    public function input(string $key = null, $default = null)
    {
        $input = json_decode(file_get_contents('php://input'), true) ?? [];
        
        if ($key === null) {
            return $input;
        }
        
        return $input[$key] ?? $default;
    }

    public function getJson()
    {
        return json_decode(file_get_contents('php://input'), true);
    }
}