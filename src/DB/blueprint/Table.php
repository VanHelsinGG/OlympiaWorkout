<?php
namespace OlympiaWorkout\DB\Blueprint;

use OlympiaWorkout\DB\DB;

class Table
{
    protected Blueprint $blueprint;
    private DB $conn;

    public function __construct(Blueprint $blueprint)
    {
        $this->blueprint = $blueprint;
        $this->conn = new DB();
    }

    public function create(): bool
    {
        $columns = $this->blueprint->getColumns();
        $primary_key = $this->blueprint->getPrimaryKey();
        $auto_increment = $this->blueprint->isAutoIncrement();

        $sql = "CREATE TABLE IF NOT EXISTS {$this->blueprint->getTable()} (";
        foreach ($columns as $column => $type) {
            $sql .= "$column $type";
            if ($primary_key === $column && $auto_increment) {
                $sql .= " AUTO_INCREMENT";
            }
            $sql .= ",";
        }

        if ($primary_key) {
            $sql .= "PRIMARY KEY ($primary_key)";
        }

        $sql = rtrim($sql, ',') . ");";

        return $this->conn->query($sql);
    }

    public function drop(): bool
    {
        return $this->conn->query("DROP TABLE IF EXISTS {$this->blueprint->getTable()}");
    }
}
