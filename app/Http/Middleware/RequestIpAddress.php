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
        // I'm not exactly happy doing this way, but it allows me to test locally
        if (env('APP_ENV') == 'local') {
            $correctIp = env('APP_TEST_IP');
        } else if (env('APP_ENV') == 'testing') {
            $correctIp = $request->server('HTTP_GGP_TEST_IP');
        } else {
            $correctIp = $request->server('REMOTE_ADDR');
        }

        $request->server->add(['GGP_REMOTE_ADDR' => $correctIp]);

        return $next($request);
    }
}
