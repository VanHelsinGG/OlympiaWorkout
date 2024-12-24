<?php
namespace OlympiaWorkout\DB\Blueprint;

class Blueprint
{
    protected string $table;
    protected array $columns = [];
    protected ?string $primary_key = null;
    protected bool $auto_increment = false;
    protected ?string $last_column = null; 

    public function __construct(string $table, callable $callback)
    {
        $this->table = $table;
        $callback($this);
    }

    public function integer(string $column): self
    {
        $this->columns[$column] = "INT";
        $this->last_column = $column;
        return $this;
    }

    public function text(string $column, int $length=255): self
    {
        $this->columns[$column] = "VARCHAR($length)";
        $this->last_column = $column;
        return $this;
    }

    public function primary(): self
    {
        if ($this->last_column) {
            $this->primary_key = $this->last_column;
        }
        return $this;
    }

    public function autoIncrement(bool $bool = true): self
    {
        $this->auto_increment = $bool;
        return $this;
    }

    public function nullable(): self
    {
        if ($this->last_column && array_key_exists($this->last_column, $this->columns)) {
            $this->columns[$this->last_column] .= " NULL";
        }
        return $this;
    }

    public function default($value): self
    {
        if($this->last_column && array_key_exists($this->last_column, $this->columns)) {
            $this->columns[$this->last_column] .= " DEFAULT '$value'";
        }
        return $this;
    }

    public function timestamps(): self
    {
        $this->columns['created_at'] = "TIMESTAMP DEFAULT CURRENT_TIMESTAMP";
        $this->columns['updated_at'] = "TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP";
        return $this;
    }

    public function getColumns(): array
    {
        return $this->columns;
    }

    public function getPrimaryKey(): ?string
    {
        return $this->primary_key;
    }

    public function isAutoIncrement(): bool
    {
        return $this->auto_increment;
    }

    public function getTable(): string
    {
        return $this->table;
    }
}
