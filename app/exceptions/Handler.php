<?php

class ErrorHandler {
    
    public static function handle404($path = '') {
        http_response_code(404);
        
        $data = [
            'title' => '404 - Halaman Tidak Ditemukan',
            'path' => $path
        ];
        
        // Tambahkan debug info jika dalam development
        if (self::isDebugMode()) {
            $data['debug'] = true;
        }
        
        self::renderError('errors/404', $data);
        exit;
    }
    
    public static function handle500($exception = null) {
        http_response_code(500);
        
        $data = [
            'title' => '500 - Server Error',
            'exception' => $exception
        ];
        
        // Log error
        if ($exception) {
            self::logError($exception);
        }
        
        self::renderError('errors/500', $data);
        exit;
    }
    
    public static function handle403() {
        http_response_code(403);
        self::renderError('errors/403', ['title' => '403 - Akses Ditolak']);
        exit;
    }
    
    public static function handle419() {
        http_response_code(419);
        self::renderError('errors/419', ['title' => '419 - Page Expired']);
        exit;
    }
    
    private static function renderError($view, $data = []) {
        // Extract data to variables
        extract($data);
        
        // Build view path
        $viewPath = BASE_PATH . '/resources/views/' . str_replace('.', '/', $view) . '.php';
        
        if (file_exists($viewPath)) {
            include $viewPath;
        } else {
            // Fallback to simple error message
            self::showFallbackError(http_response_code(), $data['title'] ?? 'Error');
        }
    }
    
    private static function showFallbackError($code, $message) {
        echo "<!DOCTYPE html>
        <html>
        <head>
            <title>$code - $message</title>
            <style>
                body { font-family: Arial, sans-serif; text-align: center; padding: 50px; }
                h1 { color: #666; }
            </style>
        </head>
        <body>
            <h1>$code</h1>
            <p>$message</p>
            <a href='/'>Kembali ke Beranda</a>
        </body>
        </html>";
    }
    
    private static function isDebugMode() {
        return $_ENV['APP_DEBUG'] ?? false;
    }
    
    private static function logError($exception) {
        $logMessage = "[" . date('Y-m-d H:i:s') . "] " .
                     get_class($exception) . ": " .
                     $exception->getMessage() . " in " .
                     $exception->getFile() . " on line " .
                     $exception->getLine() . PHP_EOL .
                     "Stack trace:" . PHP_EOL .
                     $exception->getTraceAsString() . PHP_EOL;
        
        $logFile = BASE_PATH . '/storage/logs/error.log';
        file_put_contents($logFile, $logMessage, FILE_APPEND | LOCK_EX);
    }
    
    public static function register() {
        // Register error handler
        set_error_handler(function($errno, $errstr, $errfile, $errline) {
            throw new ErrorException($errstr, 0, $errno, $errfile, $errline);
        });
        
        // Register exception handler
        set_exception_handler(function($exception) {
            self::handle500($exception);
        });
        
        // Register shutdown function for fatal errors
        register_shutdown_function(function() {
            $error = error_get_last();
            if ($error && in_array($error['type'], [E_ERROR, E_PARSE, E_CORE_ERROR, E_COMPILE_ERROR])) {
                self::handle500(new ErrorException(
                    $error['message'], 0, $error['type'], $error['file'], $error['line']
                ));
            }
        });
    }
}