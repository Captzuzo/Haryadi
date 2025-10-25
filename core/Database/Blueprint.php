<?php
namespace Haryadi\Core\Database;

class Blueprint
{
    private string $table;
    private bool $isModify;
    private array $columns = [];

    public function __construct(string $table, bool $isModify = false)
    {
        $this->table = $table;
        $this->isModify = $isModify;
    }

    public function id(string $column = 'id'): Column
    {
        // PERBAIKAN: Jangan panggil method $column() secara dinamis
        // Gunakan integer() untuk membuat kolom ID
        return $this->integer($column)->unsigned()->autoIncrement()->primary();
    }

    public function string(string $column, int $length = 255): Column
    {
        return $this->addColumn('string', $column, ['length' => $length]);
    }

    public function text(string $column): Column
    {
        return $this->addColumn('text', $column);
    }

    public function integer(string $column): Column
    {
        return $this->addColumn('integer', $column);
    }

    public function bigInteger(string $column): Column
    {
        return $this->addColumn('bigInteger', $column);
    }

    public function boolean(string $column): Column
    {
        return $this->addColumn('boolean', $column);
    }

    public function timestamp(string $column): Column
    {
        return $this->addColumn('timestamp', $column);
    }

    public function timestamps(): void
    {
        $this->timestamp('created_at')->nullable()->useCurrent();
        $this->timestamp('updated_at')->nullable()->useCurrent()->useCurrentOnUpdate();
    }

    public function rememberToken(): void
    {
        $this->string('remember_token', 100)->nullable();
    }

    public function foreignId(string $column): Column
    {
        return $this->bigInteger($column)->unsigned();
    }

    private function addColumn(string $type, string $name, array $parameters = []): Column
    {
        $column = new Column($type, $name, $parameters);
        $this->columns[] = $column;
        return $column;
    }

    public function toSql(): string
    {
        if ($this->isModify) {
            return $this->toAlterSql();
        }

        return $this->toCreateSql();
    }

    private function toCreateSql(): string
    {
        $columns = [];
        $primaryKeys = [];
        
        foreach ($this->columns as $column) {
            $columns[] = $column->toSql();
            if ($column->isPrimary()) {
                $primaryKeys[] = "`{$column->getName()}`";
            }
        }

        $columnsSql = implode(",\n    ", $columns);
        
        if (!empty($primaryKeys)) {
            $columnsSql .= ",\n    PRIMARY KEY (" . implode(', ', $primaryKeys) . ")";
        }

        return "CREATE TABLE IF NOT EXISTS `{$this->table}` (\n    {$columnsSql}\n) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
    }

    private function toAlterSql(): string
    {
        if (empty($this->columns)) {
            return '';
        }

        $modifications = [];
        foreach ($this->columns as $column) {
            $modifications[] = "ADD COLUMN {$column->toSql()}";
        }

        $modificationsSql = implode(",\n    ", $modifications);
        return "ALTER TABLE `{$this->table}`\n    {$modificationsSql}";
    }
}