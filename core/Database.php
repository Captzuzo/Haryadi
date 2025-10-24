<?php
namespace Haryadi\Core;

use PDO;
use PDOException;

class Database
{
    private $connection;
    private static $instance = null;

    public function __construct()
    {
        // Jika APP_DEBUG=false, jangan connect otomatis
        if (env('APP_DEBUG', 'true') === 'false') {
            return;
        }
        
        $this->connect();
    }

    private function connect(): void
    {
        $config = [
            'host' => env('DB_HOST', 'localhost'),
            'database' => env('DB_NAME', 'haryadi_app'),
            'username' => env('DB_USER', 'root'),
            'password' => env('DB_PASS', ''),
            'charset' => 'utf8mb4',
            'port' => env('DB_PORT', '3306')
        ];

        try {
            $dsn = "mysql:host={$config['host']};port={$config['port']};charset={$config['charset']}";
            
            // First connect without database to check if we need to create it
            $this->connection = new PDO($dsn, $config['username'], $config['password'], [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
            ]);

            // Check if database exists, if not create it
            $this->ensureDatabaseExists($config['database']);

            // Now connect with database
            $dsn = "mysql:host={$config['host']};dbname={$config['database']};charset={$config['charset']}";
            $this->connection = new PDO($dsn, $config['username'], $config['password'], [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
            ]);

        } catch (PDOException $e) {
            // Jika dalam mode development, tampilkan error yang friendly
            if (env('APP_DEBUG', 'true') === 'true') {
                $this->handleDatabaseError($e, $config);
            } else {
                throw new \Exception("Database connection failed");
            }
        }
    }

    private function ensureDatabaseExists(string $databaseName): void
    {
        try {
            // Try to use the database
            $this->connection->exec("USE `$databaseName`");
        } catch (PDOException $e) {
            // Database doesn't exist, create it
            $this->createDatabase($databaseName);
        }
    }

    private function createDatabase(string $databaseName): void
    {
        $this->connection->exec("CREATE DATABASE IF NOT EXISTS `$databaseName` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
        $this->connection->exec("USE `$databaseName`");
        
        // Create basic users table if needed
        $this->createDefaultTables();
    }

    private function createDefaultTables(): void
    {
        $usersTable = "
            CREATE TABLE IF NOT EXISTS `users` (
                `id` INT AUTO_INCREMENT PRIMARY KEY,
                `name` VARCHAR(100) NOT NULL,
                `email` VARCHAR(255) UNIQUE NOT NULL,
                `password` VARCHAR(255) NOT NULL,
                `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
        ";

        $this->connection->exec($usersTable);
    }

    private function handleDatabaseError(PDOException $e, array $config): void
    {
        $errorMessage = $e->getMessage();
        
        echo "<div style='font-family: Arial, sans-serif; padding: 20px; background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; border-radius: 5px;'>";
        echo "<h2>Database Connection Error</h2>";
        echo "<p><strong>Error:</strong> " . htmlspecialchars($errorMessage) . "</p>";
        echo "<p><strong>Solution:</strong> Please follow these steps:</p>";
        echo "<ol>";
        echo "<li>Create a MySQL database named: <code>{$config['database']}</code></li>";
        echo "<li>Check your database credentials in <code>.env</code> file</li>";
        echo "<li>Make sure MySQL server is running</li>";
        echo "</ol>";
        echo "<p><strong>Quick fix:</strong> Run this SQL command:</p>";
        echo "<pre style='background: #fff; padding: 10px; border-radius: 3px;'>CREATE DATABASE {$config['database']};</pre>";
        echo "</div>";
        exit;
    }

    public function getConnection(): PDO
    {
        if ($this->connection === null) {
            $this->connect();
        }
        return $this->connection;
    }

    public static function getInstance(): self
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    // Query builder methods
    public function table(string $table): QueryBuilder
    {
        return new QueryBuilder($this->getConnection(), $table);
    }
}