<?php
namespace Haryadi\Models;

use PDO;

class Model
{
    protected static ?PDO $pdo = null;
    protected static string $table = '';

    protected static function getConnection(): PDO
    {
        if (self::$pdo) {
            return self::$pdo;
        }

        $dsn = env('DB_DSN', 'sqlite::memory:');
        $user = env('DB_USER', null);
        $pass = env('DB_PASS', null);

        self::$pdo = new PDO($dsn, $user, $pass);
        self::$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return self::$pdo;
    }

    public static function all(): array
    {
        $tbl = static::$table ?: strtolower((new \ReflectionClass(static::class))->getShortName()) . 's';
        $stmt = self::getConnection()->query("SELECT * FROM $tbl");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
