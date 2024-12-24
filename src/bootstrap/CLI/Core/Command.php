<?php

namespace OlympiaWorkout\bootstrap\CLI\Core;

use Exception;

class Command
{
    public static function run(string $command, string $group, array $params): void
    {
        $command = ucfirst($command);
        $group   = ucfirst($group);
        $command = "OlympiaWorkout\\bootstrap\\CLI\\Commands\\$group\\$command";

        if (!class_exists($command)) {
            throw new Exception("'$command' not found.");
        }

        $command = new $command();
        if (!method_exists($command, 'handle')) {
            throw new Exception("'handle' method not found in '$command'.");
        }

        try{
            $command->handle($params);
        } catch (Exception $e) {
            throw new Exception("{$e->getMessage()}");
        }
    }
}
