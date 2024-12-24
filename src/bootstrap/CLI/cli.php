<?php

namespace OlympiaWorkout\bootstrap\CLI;

use Exception;
use OlympiaWorkout\bootstrap\CLI\Core\Command;

require_once realpath(__DIR__ . '/../../../vendor/autoload.php');

$args = $argv;
array_shift($args);

if ($args[0] === 'help') {
    $command = 'Help';
    $group   = 'Sys';
} else {
    $commandExploded =   explode(':', $args[0])            ?? null;
    $group           =   $commandExploded[0]               ?? null;
    $command         =   $commandExploded[1]               ?? null;
}

$params = array_slice($args, 1);

if (!file_exists(__DIR__ . "/Commands/$group/$command.php")) {
    echo "[CLI] Command Error: '$command' not found.\n";
    die(1);
}

try {
    Command::run($command, $group, $params);
} catch (Exception $e) {
    echo "[CLI] {$e->getMessage()}.\n";
    die(1);
}
