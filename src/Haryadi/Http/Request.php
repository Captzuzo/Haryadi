<?php
namespace Haryadi\Http;

class Request
{
    protected array $get = [];
    protected array $post = [];
    protected array $server = [];

    public function __construct(array $get = [], array $post = [], array $server = [])
    {
        $this->get = $get;
        $this->post = $post;
        $this->server = $server;
    }

    public static function capture(): self
    {
        return new self($_GET, $_POST, $_SERVER);
    }

    public function input(string $key, $default = null)
    {
        return $this->post[$key] ?? $this->get[$key] ?? $default;
    }

    public function all(): array
    {
        return array_merge($this->get, $this->post);
    }

    public function method(): string
    {
        return strtoupper($this->server['REQUEST_METHOD'] ?? 'GET');
    }

    public function path(): string
    {
        $uri = $this->server['REQUEST_URI'] ?? '/';
        $qpos = strpos($uri, '?');
        if ($qpos === false) {
            $clean = rtrim($uri, '/') ?: '/';
        } else {
            $clean = rtrim(substr($uri, 0, $qpos), '/') ?: '/';
        }
        return $clean;
    }
}
