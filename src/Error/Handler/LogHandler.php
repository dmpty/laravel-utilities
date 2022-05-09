<?php

namespace Dmpty\LaravelUtilities\Error\Handler;

use Dmpty\LaravelUtilities\Common\Jobs\LogCreate;
use Dmpty\LaravelUtilities\Error\ErrorLog;
use Dmpty\LaravelUtilities\Error\Exceptions\ExceptionInterface;
use Exception;

class LogHandler
{
    public static function handle(Exception $e): bool
    {
        $data = [];
        if ($e instanceof ExceptionInterface) {
            if (!$e->isReportable()) {
                return false;
            }
            $data = $e->getData();
        }
        $msg = $e->getMessage();
        $msg = strlen($msg) > 511 ? substr($msg, 0, 508) . '...' : $msg;
        LogCreate::dispatch(ErrorLog::class, [
            'message' => $msg,
            'code' => $e->getCode(),
            'file' => $e->getFile(),
            'line' => $e->getLine(),
            'trace' => array_slice($e->getTrace(), 0, 32),
            'data' => $data,
        ]);
        return true;
    }
}
