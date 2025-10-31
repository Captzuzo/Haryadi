<?php
namespace Haryadi\Exceptions;

class Handler
{
    public static function register(): void
    {
        set_exception_handler([self::class, 'handleException']);
        set_error_handler([self::class, 'handleError']);
    }

    public static function handleException(\Throwable $e): void
    {
        http_response_code(500);
        if (php_sapi_name() === 'cli') {
            echo "Exception: " . $e->getMessage() . PHP_EOL;
            return;
        }

        echo "<h1>Application Error</h1>";
        echo "<p>" . htmlspecialchars($e->getMessage()) . "</p>";
    }

    public static function handleError(int $errno, string $errstr, string $errfile, int $errline): bool
    {
        throw new \ErrorException($errstr, 0, $errno, $errfile, $errline);
    }
}
