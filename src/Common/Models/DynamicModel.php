<?php

namespace Dmpty\LaravelUtilities\Common\Models;

use Dmpty\LaravelUtilities\Common\Contacts\HasTableService;
use Dmpty\LaravelUtilities\Common\Contacts\TableService;
use Dmpty\LaravelUtilities\Common\Services\SafeQuery;

class DynamicModel extends BaseModel implements HasTableService
{
    public string|TableService $tableService;

    public function __construct(array $attributes = [], $suffix = '')
    {
        parent::__construct($attributes);
        if ($suffix) {
            $tableName = $this->table . '_' . $suffix;
            $this->setTable($tableName);
        }
    }

    public function getTableService(): TableService
    {
        return $this->tableService;
    }

    public static function safeQuery($suffix = '', $force = true): SafeQuery
    {
        return new SafeQuery(new static([], $suffix), $force);
    }
}
