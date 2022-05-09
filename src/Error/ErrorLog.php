<?php

namespace Dmpty\LaravelUtilities\Error;

use Dmpty\LaravelUtilities\Common\Models\DailyModel;
use Dmpty\LaravelUtilities\Error\Database\ErrorLogTableService;

class ErrorLog extends DailyModel
{
    protected $table = 'error_logs';

    public $tableService = ErrorLogTableService::class;

    protected $casts = [
        'trace' => 'array',
        'data' => 'array',
    ];
}
