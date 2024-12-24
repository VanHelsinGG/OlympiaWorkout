<?php

declare(strict_types=1);

namespace OlympiaWorkout\DB;

use mysqli;
use RuntimeException;

class DB
{
    private ?mysqli $conn = null;

    public function __construct(
        string $hostname = "localhost",
        string $username = "root",
        string $password = "",
        string $database = "db_olympiaworkout",
        int $port = 3306
    ) {
        try {
            $this->createDBIfNotExists($hostname, $username, $password, $database);

            $this->conn = new mysqli($hostname, $username, $password, $database, $port);

            if ($this->conn->connect_errno) {
                throw new RuntimeException(
                    "Failed to connect to MySQL: [{$this->conn->connect_errno}] {$this->conn->connect_error}"
                );
            }
        } catch (\mysqli_sql_exception $e) {
            throw new RuntimeException("MySQL connection error: " . $e->getMessage(), $e->getCode(), $e);
        }
    }

    public function query($query){
        return $this->conn->query($query);
    }

    private function createDBIfNotExists($hostname, $username, $password, $database): void
    {
        $conn = new mysqli($hostname, $username, $password);
        $sql = "CREATE DATABASE IF NOT EXISTS {$database}";
        $conn->query($sql);
        $conn->close();
    }

    public function close(): void
    {
        if ($this->conn) {
            $this->conn->close();
        }
    }

    private function getTypes(array $data): string
    {
        return implode("", array_map(fn($value) => match (gettype($value)) {
            'integer' => 'i',
            'double'  => 'd',
            'string'  => 's',
            default   => 's',
        }, $data));
    }

    private function buildWhere(array $where): array
    {
        if (empty($where)) {
            return ["", []];
        }

        $whereClause = " WHERE " . implode(" AND ", array_map(fn($key) => "{$key} = ?", array_keys($where)));
        return [$whereClause, array_values($where)];
    }

    public function insert(string $table, array $data): bool
    {
        $placeholders = implode(", ", array_fill(0, count($data), "?"));
        $fields = implode(", ", array_keys($data));
        $sql = "INSERT INTO {$table} ({$fields}) VALUES ({$placeholders})";

        $stmt = $this->conn->prepare($sql);
        if (!$stmt) {
            throw new RuntimeException("Failed to prepare statement: {$this->conn->error}");
        }

        $types = $this->getTypes($data);
        $stmt->bind_param($types, ...array_values($data));

        $result = $stmt->execute();
        $stmt->close();

        return $result;
    }

    public function select(string $table, array $fields = [], array $where = []): array
    {
        $fields = empty($fields) ? "*" : implode(", ", $fields);
        [$whereClause, $whereValues] = $this->buildWhere($where);

        $sql = "SELECT {$fields} FROM {$table}{$whereClause}";

        $stmt = $this->conn->prepare($sql);
        if (!$stmt) {
            throw new RuntimeException("Failed to prepare statement: {$this->conn->error}");
        }

        if (!empty($whereValues)) {
            $types = $this->getTypes($whereValues);
            $stmt->bind_param($types, ...$whereValues);
        }

        $stmt->execute();
        $result = $stmt->get_result();
        $data = $result->fetch_all(MYSQLI_ASSOC);

        $stmt->close();
        return $data;
    }

    public function update(string $table, array $data, array $where): bool
    {
        $fields = implode(", ", array_map(fn($key) => "{$key} = ?", array_keys($data)));
        [$whereClause, $whereValues] = $this->buildWhere($where);

        $sql = "UPDATE {$table} SET {$fields}{$whereClause}";

        $stmt = $this->conn->prepare($sql);
        if (!$stmt) {
            throw new RuntimeException("Failed to prepare statement: {$this->conn->error}");
        }

        $types = $this->getTypes(array_merge($data, $whereValues));
        $values = array_merge(array_values($data), $whereValues);

        $stmt->bind_param($types, ...$values);
        $result = $stmt->execute();
        $stmt->close();

        return $result;
    }

    public function delete(string $table, array $where): bool
    {
        [$whereClause, $whereValues] = $this->buildWhere($where);
        $sql = "DELETE FROM {$table}{$whereClause}";

        $stmt = $this->conn->prepare($sql);
        if (!$stmt) {
            throw new RuntimeException("Failed to prepare statement: {$this->conn->error}");
        }

        if (!empty($whereValues)) {
            $types = $this->getTypes($whereValues);
            $stmt->bind_param($types, ...$whereValues);
        }

        $result = $stmt->execute();
        $stmt->close();

        return $result;
    }
}
