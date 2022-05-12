<?php

namespace Dmpty\LaravelUtilities\ApiLog\Middleware;

use Closure;
use Dmpty\LaravelUtilities\ApiLog\Models\ApiLog;
use Illuminate\Http\Request;

class ApiReqLog
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next): mixed
    {
        app()->singleton('reqData', function () {
            $log = ApiLog::safeQuery()->create([
                'ip' => request()->ip(),
                'url' => request()->url(),
                'req' => [
                    'headers' => request()->header(),
                    'data' => request()->all(),
                    'content' => request()->getContent(),
                ],
                'res' => [],
            ]);
            return [
                'id' => $log['id'],
                'table' => $log->getTable(),
                'req_id' => $log['req_id'],
            ];
        });
        return $next($request);
    }
}
