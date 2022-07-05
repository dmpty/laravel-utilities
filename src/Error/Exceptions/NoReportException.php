<?php

namespace Dmpty\LaravelUtilities\Error\Exceptions;

class NoReportException extends BaseException
{
    protected bool $reportable = false;
}
