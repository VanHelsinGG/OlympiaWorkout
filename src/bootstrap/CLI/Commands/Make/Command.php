<?php
namespace OlympiaWorkout\bootstrap\CLI\Commands\Make;

use Exception;

class Command
{
    public $description = "Correct Usage: make:command <group>:<name>\nDescription: Create a new command.";

    public function handle(array $params): void
    {
        $params = ($params) ? explode(':', $params[0]) : null;
        $group  =  $params[0] ?? null;
        $name   =  $params[1] ?? null;

        $group = ucfirst($group);
        $name  = ucfirst($name);

        if(!$name || !$group || !$params){
            throw new Exception("Correct usage: make:command <group>:<name>");
        }

        $path = __DIR__ . DIRECTORY_SEPARATOR . ".." . DIRECTORY_SEPARATOR . $group . DIRECTORY_SEPARATOR . "$name.php";
        $dir  = dirname($path);

        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }
        
        if(file_exists($path)){
            throw new Exception("Command '$group:$name' already exists");
        }

        $content = '<?php\n\nnamespace OlympiaWorkout\\bootstrap\\CLI\\Commands\\'.$group.';\n\nclass '.$name.'\n{\n    public \$description = "command description";\n\n    public function handle(array $params): void\n    {\n        // Your code here\n    }\n}\n';

        file_put_contents($path, $content);

        echo "[CLI] Command '$group:$name' created successfully.\n";
    }
}