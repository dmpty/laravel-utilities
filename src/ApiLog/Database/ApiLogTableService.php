<?php

namespace Dmpty\LaravelUtilities\ApiLog\Database;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Dmpty\LaravelUtilities\Common\Contacts\TableService;

class ApiLogTableService implements TableService
{
    public static function create(string $tableName)
    {
        $connection = Schema::connection('logs');
        if ($connection->hasTable($tableName)) {
            return;
        }
        $connection->create($tableName, function (Blueprint $table) {
            $table->id();
            $table->unsignedTinyInteger('direction')->default(0)->comment('0:接收;1:发送');
            $table->unsignedTinyInteger('type')->default(0)->comment('类型');
            $table->string('ip', 15)->default('')->comment('请求方IP');
            $table->string('url', 511)->comment('请求URL');
            $table->json('req')->comment('请求内容');
            $table->json('res')->comment('响应内容');
            $table->timestamp('created_at');
        });
    }
}
