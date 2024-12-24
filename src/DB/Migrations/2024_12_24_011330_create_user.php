<?php

namespace OlympiaWorkout\DB\Migrations;

use OlympiaWorkout\DB\Blueprint\Blueprint;
use OlympiaWorkout\DB\Blueprint\Table;

class CreateUsr
{
    private $tableName = 'user';

    public function up(): bool
    {
        $blueprint = new Blueprint('user', function ($table) {
            $table->integer('id')->primary()->autoIncrement(true);
            $table->text('name');
            $table->timestamps();
        });

        return (new Table($blueprint))->create();
    }

    public function down(): bool
    {
        $blueprint = new Blueprint($this->tableName, function () {});
        return (new Table($blueprint))->drop();
    }
}
