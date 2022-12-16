<?php

namespace App\Http\Middleware;

use Carbon\Carbon;
use Closure;
use Illuminate\Http\Request;

class RequestsCounting
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse) $next
     * @param int $requestCount
     * @param int $period
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function handle(Request $request, Closure $next, $requestCount = 60, $period = 60)
    {
        $diffTime = Carbon::now()->timestamp - session('timestamp_first');

        if ($diffTime > $period && session('attempt') > $requestCount) {
            session()->forget(['timestamp_first', 'attempt']);
        }

        if (!session()->has('timestamp_first')) {
            session()->put('timestamp_first', Carbon::now()->timestamp);
        }


        session()->increment('attempt');
        dump(session()->all());

        if (
            session('attempt') > $requestCount
            && $diffTime <= $period
        ) {
            $time = $period - $diffTime;
            abort(429, "Too many requests. Wait: {$time}");
        }

        return $next($request);
    }

}
