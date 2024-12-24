<?php

namespace OlympiaWorkout\bootstrap\CLI\Commands\Db;

class Fresh
{
    public $description = "Correct Usage: db:fresh\nDescription: Drop all tables and re-run all migrations.";

    public function handle(): void
    {
        $migrationDir = __DIR__ . DIRECTORY_SEPARATOR . ".." . DIRECTORY_SEPARATOR . ".." . DIRECTORY_SEPARATOR . ".." . DIRECTORY_SEPARATOR . ".." . DIRECTORY_SEPARATOR . "DB" . DIRECTORY_SEPARATOR . "Migrations";
        if (!$migrationDir) {
            echo "[CLI] Migration directory not found.\n";
            return;
        }

        $files = glob($migrationDir . DIRECTORY_SEPARATOR . '*.php');

        if (!$files) {
            echo "[CLI] No migration files found.\n";
            return;
        }

        foreach ($files as $file) {
            require_once $file;

            $fileName = basename($file, '.php');
            $parts = explode('_', $fileName);
            $tableName = end($parts);

            $className = 'Create' . ucfirst($tableName);
            $classNamespace = "OlympiaWorkout\\DB\\Migrations\\$className";

            echo "[CLI] Migration $fileName.........";

            if (!class_exists($classNamespace)) {
                echo "FAILED: Class '$className' not found.\n";
                continue;
            }

            $migration = new $classNamespace();

            $missingMethods = array_filter(['down', 'up'], fn($method) => !method_exists($migration, $method));
            if (!empty($missingMethods)) {
                echo "FAILED: Missing method(s): " . implode(', ', $missingMethods) . ".\n";
                continue;
            }

            $downSuccess = $migration->down();
            $upSuccess = $migration->up();

            echo ($downSuccess && $upSuccess) ? "SUCCESS\n" : "FAILED\n";
        }
    }
}
