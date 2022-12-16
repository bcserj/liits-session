<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RequestsCounting
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @param int $requestCount
     * @param  int $period
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next, $requestCount = 60, $period = 60)
    {
        return $next($request);
    }
}
