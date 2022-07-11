<?php

use Dmpty\LaravelUtilities\Common\Jobs\LogUpdate;
use Dmpty\LaravelUtilities\Error\Exceptions\NoReportException;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

if (!function_exists('content')) {
    function content(string $content, int $status = 200): Response|Application|ResponseFactory
    {
        return response($content, $status);
    }
}

if (!function_exists('json')) {
    function json(array $data = [], int $status = 1, string $msg = '', int $code = 200): JsonResponse
    {
        $jsonData = [
            'status' => $status,
            'msg' => $msg,
            'data' => $data,
        ];
        if (app()->has('reqData')) {
            $reqData = app('reqData');
            $reqData['res'] = $jsonData;
            LogUpdate::dispatch($reqData);
            $jsonData['req_id'] = $reqData['req_id'];
        }
        return response()->json($jsonData, $code);
    }
}

if (!function_exists('error')) {
    function error(Throwable $e, $code = 500): JsonResponse
    {
        if ($e instanceof \Symfony\Component\HttpKernel\Exception\HttpException) {
            $code = $e->getStatusCode();
        }
        if ($e instanceof NoReportException) {
            $code = $e->getStatus();
            $msg = $e->getMessage();
        } elseif (!is_prod()) {
            $msg = $e->getMessage();
        } else {
            $msg = 'Internal Server Error';
        }
        return json([], 0, $msg, $code);
    }
}

if (!function_exists('msg')) {
    function msg(string $msg, int $status = 1): JsonResponse
    {
        return json([], $status, $msg);
    }
}

if (!function_exists('success')) {
    function success(string $msg = 'Success'): JsonResponse
    {
        return msg($msg);
    }
}

if (!function_exists('fail')) {
    function fail(string $msg = 'Fail'): JsonResponse
    {
        return msg($msg, 0);
    }
}

if (!function_exists('is_prod')) {
    function is_prod(): bool
    {
        return env('APP_ENV') === 'production';
    }
}

if (!function_exists('mtime')) {
    function mtime(): int
    {
        list($micro, $time) = explode(' ', microtime());
        return $time * 1000 + intval($micro * 1000);
    }
}

if (!function_exists('json2array')) {
    function json2array($json): array
    {
        try {
            $res = json_decode($json, 1);
            if (!is_array($res)) {
                return [];
            }
            return $res;
        } /** @noinspection PhpUnusedLocalVariableInspection */ catch (Exception $e) {
            return [];
        }
    }
}

if (!function_exists('md5_sign')) {
    function md5_sign(array $data, string $apiKey): string
    {
        $str = md5_sign_get_str($data) . '&apiKey=' . $apiKey;
        return strtoupper(md5($str));
    }
}

if (!function_exists('md5_sign_get_str')) {
    function md5_sign_get_str(array $arr, string $parentKey = ''): string
    {
        ksort($arr);
        $data = [];
        foreach ($arr as $childKey => $value) {
            if ($value === '') {
                continue;
            }
            if ($parentKey) {
                $key = $parentKey . '.' . $childKey;
            } else {
                $key = $childKey;
            }
            if (is_array($value)) {
                $data[$key] = md5_sign_get_str($value, $key);
            } else {
                $data[$key] = $key . '=' . $value;
            }
        }
        return implode('&', $data);
    }
}
