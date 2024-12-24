<?php

namespace OlympiaWorkout\bootstrap\CLI;

use Exception;
use OlympiaWorkout\bootstrap\CLI\Core\CLI;
use OlympiaWorkout\bootstrap\CLI\Core\Command;

require_once realpath(__DIR__ . '/../../../vendor/autoload.php');

$args = array_slice($argv, 1);

$command = 'Help';
$group   = 'Sys';

if (!empty($args) && $args[0] !== 'help') {
    if (strpos($args[0], ':') !== false) {
        [$group, $command] = array_pad(explode(':', $args[0]), 2, null);
    } else {
        $command = $args[0];
    }
}

$commandPath = __DIR__ . "/Commands/$group/$command.php";
if (!file_exists($commandPath)) {
    CLI::error("Command '$command' not found in group '$group'");
}

try {
    $params = array_slice($args, 1);
    Command::run($command, $group, $params);
} catch (Exception $e) {
    CLI::error("Error: {$e->getMessage()}");
}