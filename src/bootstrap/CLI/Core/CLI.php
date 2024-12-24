<?php

namespace OlympiaWorkout\bootstrap\CLI\Core;

use OlympiaWorkout\bootstrap\Kernel\Enums\Color;

class CLI
{
    public static function error($message, $prefix = true, $eol = true, $die = true): void
    {
        echo Color::RED->value . ($prefix ? "[CLI] " : "") . "Error: $message." . Color::RESET->value;
        if ($eol) {
            echo "\n";
        }
        if ($die) {
            die(1);
        }
    }

    public static function success($message, $prefix = true, $eol = true): void
    {
        echo Color::GREEN->value . ($prefix ? "[CLI] " : "") . "$message." . Color::RESET->value;
        if ($eol) {
            echo "\n";
        }
    }

    public static function info($message, $prefix = true, $eol = true): void
    {
        echo Color::BLUE->value . ($prefix ? "[CLI] " : "") . "$message." . Color::RESET->value;
        if ($eol) {
            echo "\n";
        }
    }

    public static function warning($message, $prefix = true, $eol = true): void
    {
        echo Color::YELLOW->value . ($prefix ? "[CLI] " : "") . "Warning: $message." . Color::RESET->value;
        if ($eol) {
            echo "\n";
        }
    }
}
