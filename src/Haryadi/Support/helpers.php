<?php
use Haryadi\Http\Request;
use Haryadi\Http\Response;

if (!function_exists('env')) {
    function env(string $key, $default = null)
    {
        $val = getenv($key);
        if ($val === false) return $default;
        return $val;
    }
}

if (!function_exists('view')) {
    function view(string $name, array $data = [])
    {
        return \Haryadi\View\View::render($name, $data);
    }
}

if (!function_exists('dd')) {
    function dd(...$args)
    {
        foreach ($args as $a) {
            var_dump($a);
        }
        exit;
    }
}

if (!function_exists('request')) {
    function request(): Request
    {
        return Request::capture();
    }
}

if (!function_exists('response')) {
    function response(string $content = '', int $status = 200): Response
    {
        return new Response($content, $status);
    }
}

if (!function_exists('asset')) {
    /**
     * asset helper
     * returns a public-relative path to an asset and appends cache-busting timestamp when available
     */
    function asset(string $path): string
    {
        $path = '/' . ltrim($path, '/\\');
        $file = __DIR__ . '/../../../../public' . $path;
        if (file_exists($file)) {
            $ts = filemtime($file);
            return $path . '?v=' . $ts;
        }
        return $path;
    }
}

// alias for older templates that use `assets()` instead of `asset()`
if (!function_exists('assets')) {
    function assets(string $path): string
    {
        return asset($path);
    }
}

if (!function_exists('e')) {
    /** Escape HTML */
    function e($value)
    {
        return htmlspecialchars((string) $value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
    }
}

if (!function_exists('csrf_token')) {
    function csrf_token(): string
    {
        // ensure session started
        if (php_sapi_name() !== 'cli' && session_status() !== PHP_SESSION_ACTIVE) {
            @session_start();
        }

        if (empty($_SESSION['_csrf_token'])) {
            // generate a random token
            try {
                $_SESSION['_csrf_token'] = bin2hex(random_bytes(16));
            } catch (Exception $e) {
                $_SESSION['_csrf_token'] = bin2hex(openssl_random_pseudo_bytes(16));
            }
        }

        return (string) $_SESSION['_csrf_token'];
    }
}

if (!function_exists('csrf_field')) {
    function csrf_field(): string
    {
        $token = csrf_token();
        return '<input type="hidden" name="_token" value="' . e($token) . '">';
    }
}

if (!function_exists('csrf_validate')) {
    /**
     * Validate CSRF token from request (POST param _token or X-CSRF-TOKEN header)
     */
    function csrf_validate(?string $token = null): bool
    {
        if (php_sapi_name() !== 'cli' && session_status() !== PHP_SESSION_ACTIVE) {
            @session_start();
        }

        if ($token === null) {
            $token = $_POST['_token'] ?? $_SERVER['HTTP_X_CSRF_TOKEN'] ?? null;
        }

        $stored = $_SESSION['_csrf_token'] ?? null;
        if (!$stored || !$token) return false;
        return hash_equals((string)$stored, (string)$token);
    }
}

if (!function_exists('method_field')) {
    function method_field(string $method): string
    {
        return '<input type="hidden" name="_method" value="' . e(strtoupper($method)) . '">';
    }
}
