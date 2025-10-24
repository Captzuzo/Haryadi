<?php
// core/helpers.php

if (!function_exists('env')) {
    /**
     * Gets the value of an environment variable.
     */
    function env(string $key, $default = null)
    {
        $value = $_ENV[$key] ?? getenv($key);
        
        if ($value === false) {
            return $default;
        }
        
        switch (strtolower($value)) {
            case 'true':
            case '(true)':
                return true;
            case 'false':
            case '(false)':
                return false;
            case 'empty':
            case '(empty)':
                return '';
            case 'null':
            case '(null)':
                return null;
        }
        
        if (preg_match('/\A([\'"])(.*)\1\z/', $value, $matches)) {
            return $matches[2];
        }
        
        return $value;
    }
}

if (!function_exists('app')) {
    /**
     * Get the available container instance.
     */
    function app(): Haryadi\Core\App
    {
        return Haryadi\Core\App::getInstance();
    }
}

if (!function_exists('view')) {
    /**
     * Render a view template.
     */
    function view(string $template, array $data = []): void
    {
        $view = new Haryadi\Core\View();
        $view->render($template, $data);
    }
}

if (!function_exists('response')) {
    /**
     * Get response instance.
     */
    function response(): Haryadi\Core\Response
    {
        return new Haryadi\Core\Response();
    }
}

if (!function_exists('redirect')) {
    /**
     * Redirect to a new URL.
     */
    function redirect(string $url, int $statusCode = 302): void
    {
        http_response_code($statusCode);
        header("Location: $url");
        exit;
    }
}

if (!function_exists('asset')) {
    /**
     * Generate asset URL.
     */
    function asset(string $path): string
    {
        $baseUrl = env('APP_URL', 'http://localhost');
        return rtrim($baseUrl, '/') . '/assets/' . ltrim($path, '/');
    }
}

if (!function_exists('config')) {
    /**
     * Get configuration value
     */
    function config(string $key, $default = null)
    {
        $configs = [
            // App Config
            'app.name' => env('APP_NAME', 'Haryadi Framework'),
            'app.env' => env('APP_ENV', 'local'),
            'app.debug' => env('APP_DEBUG', false),
            'app.url' => env('APP_URL', 'http://localhost:8000'),
            'app.key' => env('APP_KEY', ''),
            
            // Localization
            'app.locale' => env('APP_LOCALE', 'id'),
            'app.fallback_locale' => env('APP_FALLBACK_LOCALE', 'id'),
            
            // Database
            'database.connection' => env('DB_CONNECTION', 'mysql'),
            'database.host' => env('DB_HOST', '127.0.0.1'),
            'database.port' => env('DB_PORT', '3306'),
            'database.name' => env('DB_DATABASE', 'haryadi'),
            'database.username' => env('DB_USERNAME', 'root'),
            'database.password' => env('DB_PASSWORD', ''),
            
            // Session
            'session.driver' => env('SESSION_DRIVER', 'file'),
            'session.lifetime' => env('SESSION_LIFETIME', 120),
            
            // Cache
            'cache.driver' => env('CACHE_DRIVER', 'file'),
        ];
        
        return $configs[$key] ?? $default;
    }
}

if (!function_exists('app_key')) {
    /**
     * Get application key (decode dari base64 jika perlu)
     */
    function app_key(): string
    {
        $key = env('APP_KEY', '');
        
        // Jika format base64, decode
        if (str_starts_with($key, 'base64:')) {
            return base64_decode(substr($key, 7));
        }
        
        return $key;
    }
}

if (!function_exists('dd')) {
    /**
     * Dump and die.
     */
    function dd(...$vars): void
    {
        foreach ($vars as $var) {
            echo '<pre>';
            var_dump($var);
            echo '</pre>';
        }
        exit(1);
    }
}

if (!function_exists('abort')) {
    /**
     * Throw an HTTP exception.
     */
    function abort(int $code, string $message = ''): void
    {
        http_response_code($code);
        
        if ($message) {
            echo $message;
        } else {
            $messages = [
                404 => 'Page Not Found',
                500 => 'Internal Server Error',
                403 => 'Forbidden',
            ];
            echo $messages[$code] ?? 'Error';
        }
        
        exit;
    }
}