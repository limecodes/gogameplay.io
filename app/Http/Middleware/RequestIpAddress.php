<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\App;

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
        // I'm happier in doing this through Charles, and it will only work when environment is local
        // Otherwise it'll use the standard REMOTE_ADDR header
        // To modify the server header, it'll have to be an apache setting
        if ((App::environment('production')) && ($request->server('HTTP_X_FORWARDED_FOR'))) {
            $correctIp = $request->server('HTTP_X_FORWARDED_FOR');
        } else if ((App::environment('local')) && ($request->header('X-Forwarded-For'))) {
            $correctIp = $request->header('X-Forwarded-For');
        } else {
            $correctIp = $request->server('REMOTE_ADDR');
        }

        $request->server->add(['GGP_REMOTE_ADDR' => $correctIp]);

        return $next($request);
    }
}
