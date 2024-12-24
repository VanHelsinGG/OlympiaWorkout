<?php
namespace OlympiaWorkout\bootstrap\CLI\Commands\Make;

use Exception;

class Migration
{
    public $description = "Correct Usage: make:migration <table_name>\nDescription: Create a new migration file.";

    public function handle(array $params): void
    {
        $table_name = $params[0] ?? null;

        if (!$table_name) {
            throw new Exception("Correct Usage: make:migration <table_name>");
        }

        $path = __DIR__ . "/../../../../" .DIRECTORY_SEPARATOR . "DB" . DIRECTORY_SEPARATOR . "Migrations" . DIRECTORY_SEPARATOR . date('Y_m_d_His') . "_create_{$table_name}.php";

        $dir = dirname($path);
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }

        if (file_exists($path)) {
            throw new Exception("Migration '$table_name' already exists");
        }

        $content = "<?php\n\nnamespace OlympiaWorkout\\DB\\Migrations;\n\nuse OlympiaWorkout\\DB\\Blueprint\\Blueprint;\nuse OlympiaWorkout\\DB\\Blueprint\\Table;\n\nclass Create" . ucfirst($table_name) . "\n{\n    private \$tableName = '$table_name';\n\n    public function up(): bool\n    {\n        \$blueprint = new Blueprint('$table_name', function (\$table) {\n            \$table->integer('id')->primary()->autoIncrement(true);\n            \$table->timestamps();\n        });\n\n        return (new Table(\$blueprint))->create();\n    }\n\n    public function down(): bool\n    {\n        \$blueprint = new Blueprint(\$this->tableName, function () {});\n        return (new Table(\$blueprint))->drop();\n    }\n}\n";

        file_put_contents($path, $content);

        echo "[CLI] Migration '$table_name' created successfully.\n";
    }
}
