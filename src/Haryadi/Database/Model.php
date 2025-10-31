<?php

namespace Haryadi\Database;

class Model
{
    protected static string $table;
    protected static \PDO $db;
    protected array $attributes = [];

    public function __construct()
    {
        $this->connect();
        if (!isset(static::$table)) {
            static::$table = strtolower(substr(strrchr(get_class($this), "\\"), 1)) . 's';
        }
    }

    protected function connect()
    {
        if (!isset(self::$db)) {
            $host = $_ENV['DB_HOST'] ?? 'localhost';
            $dbname = $_ENV['DB_NAME'] ?? 'test';
            $username = $_ENV['DB_USER'] ?? 'root';
            $password = $_ENV['DB_PASS'] ?? '';
            
            try {
                self::$db = new \PDO("mysql:host={$host};dbname={$dbname}", $username, $password);
                self::$db->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
            } catch (\PDOException $e) {
                throw new \Exception("Database connection failed: " . $e->getMessage());
            }
        }
    }

    public static function all(): array
    {
        $stmt = self::$db->query("SELECT * FROM " . static::$table);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public static function find($id)
    {
        $stmt = self::$db->prepare("SELECT * FROM " . static::$table . " WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    public function save(): bool
    {
        if (isset($this->attributes['id'])) {
            return $this->update();
        }
        return $this->create();
    }

    protected function create(): bool
    {
        $columns = implode(', ', array_keys($this->attributes));
        $values = implode(', ', array_fill(0, count($this->attributes), '?'));
        
        $stmt = self::$db->prepare("INSERT INTO " . static::$table . " ({$columns}) VALUES ({$values})");
        return $stmt->execute(array_values($this->attributes));
    }

    protected function update(): bool
    {
        $id = $this->attributes['id'];
        unset($this->attributes['id']);
        
        $set = implode(' = ?, ', array_keys($this->attributes)) . ' = ?';
        $stmt = self::$db->prepare("UPDATE " . static::$table . " SET {$set} WHERE id = ?");
        
        $values = array_values($this->attributes);
        $values[] = $id;
        
        return $stmt->execute($values);
    }

    public function delete(): bool
    {
        if (!isset($this->attributes['id'])) {
            return false;
        }

        $stmt = self::$db->prepare("DELETE FROM " . static::$table . " WHERE id = ?");
        return $stmt->execute([$this->attributes['id']]);
    }

    public function __get($name)
    {
        return $this->attributes[$name] ?? null;
    }

    public function __set($name, $value)
    {
        $this->attributes[$name] = $value;
    }
}