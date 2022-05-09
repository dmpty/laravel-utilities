<?php

namespace Dmpty\LaravelUtilities\Error\Database;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Dmpty\LaravelUtilities\Common\Contacts\TableService;

class ErrorLogTableService implements TableService
{
    public static function create(string $tableName)
    {
        $connection = Schema::connection('logs');
        if ($connection->hasTable($tableName)) {
            return;
        }
        $connection->create($tableName, function (Blueprint $table) {
            $table->id();
            $table->string('message', 511);
            $table->string('code');
            $table->string('file');
            $table->unsignedSmallInteger('line');
            $table->json('trace');
            $table->json('data');
            $table->timestamp('created_at');
        });
    }
}
