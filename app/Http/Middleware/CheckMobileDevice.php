<?php

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Http\Request;

class CheckMobileDevice
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
        $userAgent = $request->server->get('HTTP_USER_AGENT');
        $iPhone = stripos($userAgent, 'iPhone');
        $android = stripos($userAgent, 'Android');

        if ($iPhone) {
            $request->headers->add(['DEVICE' => 'ios']);
        } else if ($android) {
            $request->headers->add(['DEVICE' => 'android']);
        } else {
            return redirect(RouteServiceProvider::NONMOBILE);
        }

        return $next($request);
    }
}
