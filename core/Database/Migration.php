<?php
namespace Haryadi\Core\Database;

use Haryadi\Core\Database;

class Migration
{
    protected \PDO $pdo;

    public function __construct()
    {
        $db = Database::getInstance();
        $this->pdo = $db->getConnection();
    }

    /**
     * Create a new table
     */
    protected function createTable(string $table, callable $callback): void
    {
        $blueprint = new Blueprint($table);
        $callback($blueprint);
        
        $sql = $blueprint->toSql();
        $this->pdo->exec($sql);
    }

    /**
     * Modify an existing table
     */
    protected function table(string $table, callable $callback): void
    {
        $blueprint = new Blueprint($table, true);
        $callback($blueprint);
        
        $sql = $blueprint->toSql();
        if (!empty($sql)) {
            $this->pdo->exec($sql);
        }
    }

    /**
     * Drop a table
     */
    protected function dropTable(string $table): void
    {
        $sql = "DROP TABLE IF EXISTS `{$table}`";
        $this->pdo->exec($sql);
    }

    /**
     * Add foreign key constraint
     */
    protected function addForeignKey(string $table, string $column, string $references, string $on = null, string $onDelete = 'RESTRICT', string $onUpdate = 'RESTRICT'): void
    {
        $onTable = $on ?: str_replace('_id', 's', $column);
        $sql = "ALTER TABLE `{$table}` ADD CONSTRAINT fk_{$table}_{$column} 
                FOREIGN KEY (`{$column}`) REFERENCES `{$onTable}`(`id`) 
                ON DELETE {$onDelete} ON UPDATE {$onUpdate}";
        $this->pdo->exec($sql);
    }

    /**
     * Run raw SQL
     */
    protected function runSql(string $sql): void
    {
        $this->pdo->exec($sql);
    }

    /**
     * Run the migrations (harus diimplementasikan oleh child class)
     */
    public function up(): void
    {
        // Diimplementasikan oleh migration class
    }

    /**
     * Reverse the migrations (harus diimplementasikan oleh child class)
     */
    public function down(): void
    {
        // Diimplementasikan oleh migration class
    }
}