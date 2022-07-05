<?php

namespace Dmpty\LaravelUtilities\ApiLog\Models;

use Carbon\Carbon;
use Dmpty\LaravelUtilities\ApiLog\Database\ApiLogTableService;
use Dmpty\LaravelUtilities\Common\Contacts\TableService;
use Dmpty\LaravelUtilities\Common\Models\DailyModel;

class ApiLog extends DailyModel
{
    protected $table = 'api_logs';

    public string|TableService $tableService = ApiLogTableService::class;

    protected $casts = [
        'req' => 'array',
        'res' => 'array',
    ];

    public function getReqIdAttribute(): string
    {
        return (new Carbon($this->attributes['created_at']))->format('ymd') . sprintf('%06d', $this['id']);
    }
}
