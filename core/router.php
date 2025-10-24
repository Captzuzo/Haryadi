<?php
namespace Haryadi\Core;

// session_start(); // Pindahkan ke constructor

class Router
{
    private static $instance;
    private $routes = [];
    private $namedRoutes = [];

    public function __construct()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        self::$instance = $this;
        $this->registerErrorHandlers();
    }

    public static function getInstance(): self
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    // Static methods untuk fluent API
    public static function get(string $path, $handler, ?string $name = null): self
    {
        return self::getInstance()->addRoute('GET', $path, $handler, $name);
    }

    public static function post(string $path, $handler, ?string $name = null): self
    {
        return self::getInstance()->addRoute('POST', $path, $handler, $name);
    }

    public static function put(string $path, $handler, ?string $name = null): self
    {
        return self::getInstance()->addRoute('PUT', $path, $handler, $name);
    }

    public static function delete(string $path, $handler, ?string $name = null): self
    {
        return self::getInstance()->addRoute('DELETE', $path, $handler, $name);
    }

    private function addRoute(string $method, string $path, $handler, ?string $name = null): self
    {
        $route = [
            'method' => $method,
            'path' => $path,
            'handler' => $this->parseHandler($handler),
            'pattern' => $this->convertToPattern($path),
            'name' => $name
        ];

        $this->routes[] = $route;

        if ($name) {
            $this->namedRoutes[$name] = $route;
        }

        return $this;
    }

    private function parseHandler($handler)
    {
        // Jika handler adalah array seperti ['HomeController@index']
        if (is_array($handler) && count($handler) === 1) {
            $handlerString = $handler[0];
            if (strpos($handlerString, '@') !== false) {
                list($controller, $method) = explode('@', $handlerString);
                return ['App\\Controllers\\' . $controller, $method];
            }
        }
        
        // Jika handler adalah string seperti 'HomeController@index'
        if (is_string($handler) && strpos($handler, '@') !== false) {
            list($controller, $method) = explode('@', $handler);
            return ['App\\Controllers\\' . $controller, $method];
        }

        // Jika sudah dalam format [Controller::class, 'method']
        return $handler;
    }

    private function convertToPattern(string $path): string
    {
        // Convert route parameters to regex pattern
        $pattern = preg_replace('/\{([a-zA-Z_]+)\}/', '([^/]+)', $path);
        return '#^' . $pattern . '$#';
    }

    public function registerRoutes(): void
    {
        // Load web routes
        if (file_exists(BASE_PATH . '/routes/web.php')) {
            require BASE_PATH . '/routes/web.php';
        }

        // Load API routes if exists
        if (file_exists(BASE_PATH . '/routes/api.php')) {
            require BASE_PATH . '/routes/api.php';
        }
    }

    public function dispatch(): void
    {
        $request = new Request();
        $path = $request->getPath();
        $method = $request->getMethod();

        foreach ($this->routes as $route) {
            if ($this->matchRoute($route, $path, $method)) {
                $this->executeRoute($route, $request);
                return;
            }
        }

        // 404 Not Found
        $this->handle404($path);
    }

    private function matchRoute(array $route, string $path, string $method): bool
    {
        if ($route['method'] !== $method) {
            return false;
        }

        return preg_match($route['pattern'], $path) === 1;
    }

    private function executeRoute(array $route, Request $request): void
    {
        $handler = $route['handler'];
        
        // Extract parameters from URL
        $params = $this->extractParams($route['path'], $request->getPath());
        
        try {
            if (is_callable($handler)) {
                call_user_func_array($handler, array_merge([$request], $params));
            } elseif (is_array($handler)) {
                [$controller, $method] = $handler;
                
                // Ensure controller exists
                if (!class_exists($controller)) {
                    throw new \Exception("Controller class not found: {$controller}");
                }
                
                // Create controller instance with Request dependency
                $controllerInstance = new $controller($request);
                
                if (!method_exists($controllerInstance, $method)) {
                    throw new \Exception("Method not found: {$controller}::{$method}");
                }
                
                // Pass additional parameters to method
                call_user_func_array([$controllerInstance, $method], $params);
            } else {
                throw new \Exception("Invalid route handler");
            }
        } catch (\Exception $e) {
            $this->handle500($e);
        }
    }

    private function extractParams(string $routePath, string $requestPath): array
    {
        $params = [];
        
        // Convert route path to regex pattern
        $pattern = $this->convertToPattern($routePath);
        
        if (preg_match($pattern, $requestPath, $matches)) {
            // Remove the full match (index 0)
            array_shift($matches);
            $params = $matches;
        }
        
        return $params;
    }

    /**
     * Get route by name
     */
    public function route(string $name, array $params = []): ?string
    {
        if (!isset($this->namedRoutes[$name])) {
            return null;
        }

        $route = $this->namedRoutes[$name]['path'];
        
        // Replace parameters in route
        foreach ($params as $key => $value) {
            $route = str_replace('{' . $key . '}', $value, $route);
        }
        
        return $route;
    }

    /**
     * Get all registered routes (for debugging)
     */
    public function getRoutes(): array
    {
        return $this->routes;
    }

    /**
     * Middleware chain method
     */
    public function middleware(array $middleware): self
    {
        // Get the last added route
        $lastIndex = count($this->routes) - 1;
        if ($lastIndex >= 0) {
            $this->routes[$lastIndex]['middleware'] = $middleware;
        }
        
        return $this;
    }

    /**
     * Error Handling Methods
     */
    private function registerErrorHandlers(): void
    {
        // Register error handler
        set_error_handler(function($errno, $errstr, $errfile, $errline) {
            throw new \ErrorException($errstr, 0, $errno, $errfile, $errline);
        });
        
        // Register exception handler
        set_exception_handler(function($exception) {
            $this->handle500($exception);
        });
        
        // Register shutdown function for fatal errors
        register_shutdown_function(function() {
            $error = error_get_last();
            if ($error && in_array($error['type'], [E_ERROR, E_PARSE, E_CORE_ERROR, E_COMPILE_ERROR])) {
                $this->handle500(new \ErrorException(
                    $error['message'], 0, $error['type'], $error['file'], $error['line']
                ));
            }
        });
    }

    private function handle404(string $path = ''): void
    {
        http_response_code(404);
        
        $viewPath = BASE_PATH . '/resources/views/errors/404.php';
        if (file_exists($viewPath)) {
            $data = [
                'title' => '404 - Halaman Tidak Ditemukan',
                'path' => $path
            ];
            
            // Tambahkan debug info jika dalam development
            if ($this->isDebugMode()) {
                $data['debug'] = true;
            }
            
            extract($data);
            include $viewPath;
        } else {
            // Fallback simple 404
            echo "<!DOCTYPE html>
            <html>
            <head>
                <title>404 - Halaman Tidak Ditemukan</title>
            </head>
            <body>
                <h1>404 - Halaman Tidak Ditemukan</h1>
                <p>Path: " . htmlspecialchars($path) . "</p>
                <a href='/'>Kembali ke Beranda</a>
            </body>
            </html>";
        }
        exit;
    }

    private function handle500(\Throwable $exception): void
    {
        http_response_code(500);
        
        // Log error
        $this->logError($exception);
        
        $viewPath = BASE_PATH . '/resources/views/errors/500.php';
        if (file_exists($viewPath)) {
            $data = [
                'title' => '500 - Server Error',
                'exception' => $exception,
                'debug' => $this->isDebugMode()
            ];
            
            if ($this->isDebugMode()) {
                $data['error_details'] = [
                    'message' => $exception->getMessage(),
                    'file' => $exception->getFile(),
                    'line' => $exception->getLine(),
                    'trace' => $exception->getTraceAsString(),
                    'type' => get_class($exception)
                ];
            }
            
            extract($data);
            include $viewPath;
        } else {
            echo "<!DOCTYPE html>
            <html>
            <head>
                <title>500 - Server Error</title>
                <style>
                    body { font-family: Arial, sans-serif; padding: 20px; }
                    .error-details { background: #f8f9fa; padding: 15px; border-radius: 5px; margin: 10px 0; }
                    pre { white-space: pre-wrap; word-wrap: break-word; }
                </style>
            </head>
            <body>
                <h1>500 - Server Error</h1>
                <p>Terjadi kesalahan internal server.</p>";
            
            if ($this->isDebugMode()) {
                echo "<div class='error-details'>
                    <h3>Error Details:</h3>
                    <p><strong>Message:</strong> " . htmlspecialchars($exception->getMessage()) . "</p>
                    <p><strong>File:</strong> " . htmlspecialchars($exception->getFile()) . "</p>
                    <p><strong>Line:</strong> " . $exception->getLine() . "</p>
                    <p><strong>Type:</strong> " . get_class($exception) . "</p>
                    <h4>Stack Trace:</h4>
                    <pre>" . htmlspecialchars($exception->getTraceAsString()) . "</pre>
                </div>";
            }
            
            echo "<a href='/'>Kembali ke Beranda</a>
            </body></html>";
        }
        exit;
    }

    private function isDebugMode(): bool
    {
        return ($_ENV['APP_DEBUG'] ?? false) === true;
    }

    private function logError(\Throwable $exception): void
    {
        $logMessage = "[" . date('Y-m-d H:i:s') . "] " .
                     get_class($exception) . ": " .
                     $exception->getMessage() . " in " .
                     $exception->getFile() . " on line " .
                     $exception->getLine() . PHP_EOL .
                     "Stack trace:" . PHP_EOL .
                     $exception->getTraceAsString() . PHP_EOL;
        
        $logDir = BASE_PATH . '/storage/logs';
        if (!is_dir($logDir)) {
            mkdir($logDir, 0755, true);
        }
        
        $logFile = $logDir . '/error.log';
        file_put_contents($logFile, $logMessage, FILE_APPEND | LOCK_EX);
    }

    /**
     * Get current path from request
     */
    private function getPath(): string
    {
        $path = $_SERVER['REQUEST_URI'] ?? '/';
        $position = strpos($path, '?');
        
        if ($position === false) {
            return $path;
        }
        
        return substr($path, 0, $position);
    }

    /**
     * Get current request method
     */
    private function getMethod(): string
    {
        return $_SERVER['REQUEST_METHOD'] ?? 'GET';
    }
}