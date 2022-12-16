<?php

namespace App\Http\Middleware;

use Carbon\Carbon;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class RequestsLimitsByIP
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse) $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function handle(Request $request, Closure $next, $requestCount = 60, $period = 60)
    {
        $cacheKeys = self::getCacheKeys($request, $requestCount);

        if (!cache()->has($cacheKeys['timestamp_first'])) {
            cache()->put($cacheKeys['timestamp_first'], Carbon::now()->timestamp, $period);
            cache()->put($cacheKeys['attempt'], 0, $period);
        }

        cache()->increment($cacheKeys['attempt']);

        $diffTime = Carbon::now()->timestamp - cache($cacheKeys['timestamp_first']);

        if (
            cache($cacheKeys['attempt']) > $requestCount
            && $diffTime <= $period
        ) {
            abort(429);
        }

        return $next($request);
    }

    /**
     * @param Request $request
     * @param $count
     * @param string[] $keys
     * @return array<string>
     * @throws \Exception
     */
    protected static function getCacheKeys(Request $request, $count, $keys = ['timestamp_first', 'attempt'])
    {
        if (is_array($keys) && empty($keys)) {
            throw new \Exception('Invalid keys format.');
        }

        $arModernKeys = [];
        foreach ($keys as $key) {
            $arModernKeys[$key] = md5(implode('|', [$request->ip(), $request->path(), $count, $key]));
        }
        return $arModernKeys;
    }

}
