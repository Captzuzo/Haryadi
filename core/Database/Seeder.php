<?php
// core/Database/Seeder.php

namespace Haryadi\Core\Database;

use Haryadi\Core\Database;

abstract class Seeder
{
    protected \PDO $db;

    public function __construct()
    {
        $database = Database::getInstance();
        $this->db = $database->getConnection();
    }

    /**
     * Run the database seeds.
     */
    abstract public function run(): void;

    /**
     * Call another seeder
     */
    protected function call(string $seederClass): void
    {
        if (!class_exists($seederClass)) {
            throw new \Exception("Seeder class tidak ditemukan: {$seederClass}");
        }

        $seeder = new $seederClass();
        $seeder->run();
    }

    /**
     * Output success message
     */
    protected function success(string $message): void
    {
        echo "\033[0;32m✅ {$message}\033[0m" . PHP_EOL;
    }

    /**
     * Output info message  
     */
    protected function info(string $message): void
    {
        echo "\033[0;34mℹ️  {$message}\033[0m" . PHP_EOL;
    }

    /**
     * Output error message
     */
    protected function error(string $message): void
    {
        echo "\033[0;31m❌ {$message}\033[0m" . PHP_EOL;
    }
}