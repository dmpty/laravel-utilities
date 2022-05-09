<?php

namespace Dmpty\LaravelUtilities\ApiLog\Repositories;

use Dmpty\LaravelUtilities\ApiLog\Models\ApiLog;
use Dmpty\LaravelUtilities\Common\Repository\BaseRepository;

class ApiLogRepository extends BaseRepository
{
    protected $dynamicModel = ApiLog::class;
}
