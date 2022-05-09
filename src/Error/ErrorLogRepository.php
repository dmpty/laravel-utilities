<?php

namespace Dmpty\LaravelUtilities\Error;

use Dmpty\LaravelUtilities\Common\Repository\BaseRepository;

class ErrorLogRepository extends BaseRepository
{
    protected $dynamicModel = ErrorLog::class;
}
