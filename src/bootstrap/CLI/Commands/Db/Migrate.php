<?php

namespace OlympiaWorkout\bootstrap\CLI\Commands\Db;

use OlympiaWorkout\bootstrap\CLI\Core\CLI;

class Migrate
{
    public $description = "Correct Usage: db:migrate\nDescription: Run all migrations.";

    public function handle(): void
    {
        $migrationDir = __DIR__ . DIRECTORY_SEPARATOR . ".." . DIRECTORY_SEPARATOR . ".." . DIRECTORY_SEPARATOR . ".." . DIRECTORY_SEPARATOR . ".." . DIRECTORY_SEPARATOR . "DB" . DIRECTORY_SEPARATOR . "Migrations";
        if (!is_dir($migrationDir)) {
            CLI::error("Migration directory not found");
            return;
        }

        $files = glob($migrationDir . DIRECTORY_SEPARATOR . '*.php');
        if (!$files) {
            CLI::error('No migration files found');
            return;
        }

        echo "\n";
        $starttime = microtime(true);

        foreach ($files as $file) {
            require_once $file;

            $fileName = basename($file, '.php');
            $parts = explode('_', $fileName);
            $tableName = end($parts);

            $className = 'Create' . ucfirst($tableName);
            $ClassNamespace = "OlympiaWorkout\\DB\\Migrations\\" . $className;

            echo "Migration $fileName.........";

            if (!class_exists($ClassNamespace)) {
                CLI::error("Class '$className' not found", 0, 1, 0);
                continue;
            }

            $migration = new $ClassNamespace();

            if (!method_exists($migration, 'up')) {
                CLI::error("Method 'up' not found", 0, 1, 0);
                continue;
            }

            if ($migration->up()) {
                CLI::success("SUCCESS", 0, 0);
            } else {
                CLI::error("FAILED", 0, 0, 0);
            }

            echo "\n";
        }

        $endtime = microtime(true);
        CLI::info("Time taken: " . round($endtime - $starttime, 2) . " seconds");
        echo "\n";
    }

}
