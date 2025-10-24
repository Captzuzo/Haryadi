<?php
namespace Haryadi\Core;

class Cache
{
    private $driver;
    private $config;

    public function __construct()
    {
        $this->config = [
            'driver' => $_ENV['CACHE_DRIVER'] ?? 'file',
            'path' => STORAGE_PATH . '/cache',
            'ttl' => $_ENV['CACHE_TTL'] ?? 3600
        ];

        $this->initializeDriver();
    }

    private function initializeDriver(): void
    {
        switch ($this->config['driver']) {
            case 'file':
                $this->driver = new FileCacheDriver($this->config['path']);
                break;
            case 'redis':
                $this->driver = new RedisCacheDriver();
                break;
            default:
                $this->driver = new FileCacheDriver($this->config['path']);
        }
    }

    public function get(string $key, $default = null)
    {
        return $this->driver->get($key) ?? $default;
    }

    public function set(string $key, $value, ?int $ttl = null): bool
    {
        return $this->driver->set($key, $value, $ttl ?? $this->config['ttl']);
    }

    public function remember(string $key, callable $callback, ?int $ttl = null)
    {
        $value = $this->get($key);
        
        if ($value === null) {
            $value = $callback();
            $this->set($key, $value, $ttl);
        }
        
        return $value;
    }
}