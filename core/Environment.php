<?php
namespace Haryadi\Core;

class Environment
{
    public static function load(string $basePath): void
    {
        $envFile = $basePath . '/.env';
        
        if (!file_exists($envFile)) {
            return;
        }
        
        $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        
        foreach ($lines as $line) {
            if (strpos(trim($line), '#') === 0) {
                continue;
            }
            
            list($name, $value) = self::splitLine($line);
            
            if ($name !== '' && !array_key_exists($name, $_ENV)) {
                $_ENV[$name] = $value;
                $_SERVER[$name] = $value;
                putenv("$name=$value");
            }
        }
    }
    
    private static function splitLine(string $line): array
    {
        $parts = explode('=', $line, 2);
        $name = trim($parts[0]);
        $value = isset($parts[1]) ? trim($parts[1]) : '';
        
        // Remove quotes if present
        if (preg_match('/^([\'"])(.*)\1$/', $value, $matches)) {
            $value = $matches[2];
        }
        
        return [$name, $value];
    }
}