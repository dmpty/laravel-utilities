<?php

namespace Dmpty\LaravelUtilities\Error;

use Dmpty\LaravelUtilities\Common\Models\DynamicModel;
use Dmpty\LaravelUtilities\Common\Repository\BaseRepository;

class ErrorLogRepository extends BaseRepository
{
    protected DynamicModel|string $dynamicModel = ErrorLog::class;
}
