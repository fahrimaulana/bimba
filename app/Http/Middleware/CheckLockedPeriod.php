<?php

namespace App\Http\Middleware;

use Auth, Closure, Session;

class CheckLockedPeriod
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        if (!Auth::guard($guard)->check() || $request->is('logout')) return $next($request);

        if (!Session::has('locked_year') || !Session::has('locked_month')) {
            Session::put('locked_year', (int)now()->year);
            Session::put('locked_month', (int)now()->month);

            return swalError('Session has expired. Selected year & month has been changed to current year & month.');
        }

        return $next($request);
    }
}
