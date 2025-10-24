<?php
namespace Haryadi\Core\Database;

class Column
{
    private string $name;
    private string $type;
    private array $parameters;
    private bool $nullable = false;
    private bool $primary = false;
    private bool $unique = false;
    private $default = null;
    private bool $autoIncrement = false;
    private bool $unsigned = false;
    private bool $useCurrent = false;
    private bool $useCurrentOnUpdate = false;

    public function __construct(string $type, string $name, array $parameters = [])
    {
        $this->type = $type;
        $this->name = $name;
        $this->parameters = $parameters;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function nullable(): self
    {
        $this->nullable = true;
        return $this;
    }

    public function primary(): self
    {
        $this->primary = true;
        return $this;
    }

    public function unique(): self
    {
        $this->unique = true;
        return $this;
    }

    public function default($value): self
    {
        $this->default = $value;
        return $this;
    }

    public function autoIncrement(): self
    {
        $this->autoIncrement = true;
        return $this;
    }

    public function unsigned(): self
    {
        $this->unsigned = true;
        return $this;
    }

    public function useCurrent(): self
    {
        $this->useCurrent = true;
        $this->default = 'CURRENT_TIMESTAMP';
        return $this;
    }

    public function useCurrentOnUpdate(): self
    {
        $this->useCurrentOnUpdate = true;
        return $this;
    }

    public function isPrimary(): bool
    {
        return $this->primary;
    }

    public function toSql(): string
    {
        $sql = "`{$this->name}` {$this->getTypeDefinition()}";

        if ($this->unsigned) {
            $sql .= " UNSIGNED";
        }

        if (!$this->nullable) {
            $sql .= " NOT NULL";
        } else {
            $sql .= " NULL";
        }

        if ($this->default !== null) {
            if ($this->default === 'CURRENT_TIMESTAMP') {
                $sql .= " DEFAULT CURRENT_TIMESTAMP";
            } else {
                $default = is_string($this->default) ? "'{$this->default}'" : $this->default;
                $sql .= " DEFAULT {$default}";
            }
        }

        if ($this->autoIncrement) {
            $sql .= " AUTO_INCREMENT";
        }

        if ($this->unique && !$this->primary) {
            $sql .= " UNIQUE";
        }

        if ($this->useCurrentOnUpdate) {
            $sql .= " ON UPDATE CURRENT_TIMESTAMP";
        }

        return $sql;
    }

    private function getTypeDefinition(): string
    {
        switch ($this->type) {
            case 'id':
            case 'integer':
                return 'INT';
            
            case 'bigInteger':
                return 'BIGINT';
            
            case 'string':
                $length = $this->parameters['length'] ?? 255;
                return "VARCHAR({$length})";
            
            case 'text':
                return 'TEXT';
            
            case 'boolean':
                return 'TINYINT(1)';
            
            case 'timestamp':
                return 'TIMESTAMP';
            
            case 'datetime':
                return 'DATETIME';
            
            default:
                return strtoupper($this->type);
        }
    }
}