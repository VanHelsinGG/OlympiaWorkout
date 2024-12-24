<?php

namespace OlympiaWorkout\bootstrap\CLI\Commands\Db;

class Migrate
{
    public $description = "Correct Usage: db:migrate\nDescription: Run all migrations.";
    // TODO: flag for specific migration

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

        foreach ($files as $file){
            require_once $file; 

            $fileName = basename($file, '.php');
            $parts = explode('_', $fileName);
            $tableName = end($parts);
            
            $className = 'Create' . ucfirst($tableName);
            $ClassNamespace = "OlympiaWorkout\\DB\\Migrations\\" . $className;

            echo "[CLI] Migration $fileName.........";

            if(!class_exists($ClassNamespace)){
                echo "FAILED: Class '$className' not found.\n";
                continue;
            }

            $migration = new $ClassNamespace();

            if(!method_exists($migration, 'up')){
                echo "FAILED: Method 'up' not found.\n";
            }

            if($migration->up()){
                echo "SUCCESS\n";
            } else {
                echo "FAILED\n";
            }
        }
    }   
}