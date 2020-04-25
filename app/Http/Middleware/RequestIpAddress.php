<?php

namespace App\Http\Middleware;

use Closure;

class RequestIpAddress
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (env('APP_ENV') == 'local') {
            $correctIp = $request->server('REMOTE_ADDR');
        } else if (env('APP_ENV') == 'testing') {
            $correctIp = env('APP_TEST_IP');
        } else {
            $correctIp = $request->server('REMOTE_ADDR');
        }

        $request->server->add(['GGP_REMOTE_ADDR' => $correctIp]);

        return $next($request);
    }
}
