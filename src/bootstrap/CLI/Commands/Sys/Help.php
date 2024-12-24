<?php

namespace OlympiaWorkout\bootstrap\CLI\Commands\Sys;

use OlympiaWorkout\bootstrap\Kernel\Enums\Color;

class Help
{
    public $description = 'Correct Usage: help <command>';

    public function handle(array $params): void
    {
        $commandsDir = __DIR__ . "/../";
        $directories = glob($commandsDir . '*', GLOB_ONLYDIR);

        if (!$directories) {
            echo "[CLI] No directories found.\n";
            return;
        }

        $commands = [];

        foreach ($directories as $dir) {
            $files = glob($dir . DIRECTORY_SEPARATOR . '*.php');
            
            if ($files) {
                $dirName = basename($dir);
                $commands[$dirName] = [];

                foreach ($files as $file) {
                    $command = basename($file, '.php');
                    $commands[$dirName][] = $command;
                }
            }
        }

        if (empty($params)) {
            echo COLOR::BLUE->value . "[CLI] Available commands:\n" . Color::RESET->value;
            foreach ($commands as $folder => $folderCommands) {
                echo Color::GREEN->value . "$folder:\n" . Color::RESET->value;
                foreach ($folderCommands as $command) {
                    $folder  = strtolower($folder);
                    $command = strtolower($command);
                    echo Color::YELLOW->value . "- $folder:$command\n" . Color::RESET->value;
                }
            }

            echo COLOR::BLUE->value ."[CLI] use 'php cli help <command>' for more information.\n" . Color::RESET->value;
        } else {
            $command      = $params[0];
            $commandParts = explode(':', $command);
            
            $group   = $commandParts[0];
            $command = $commandParts[1];

            if(file_exists($commandsDir . $group . DIRECTORY_SEPARATOR . $command . '.php')){
                $className = "OlympiaWorkout\\bootstrap\\CLI\\Commands\\$group\\$command";
                $command   = new $className();
                
                if(property_exists($command, 'description')){
                    echo "[CLI] $command->description\n";
                } else {
                    echo "[CLI] No description available.\n";
                }
            } else {
                echo "[CLI] Command not found.\n";
            }
        }
    }
}