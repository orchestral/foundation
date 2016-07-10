<?php

namespace Orchestra\Foundation\Http\Middleware;

use Closure;
use Orchestra\Foundation\Auth\Reauthenticate\ReauthLimiter;

class Reauthenticate
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request  $request
     * @param \Closure  $next
     *
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if ((new ReauthLimiter($request)->check())) {
            return $next($request);
        }

        $request->session()->set('url.intended', $request->url());

        return $this->invalidated($request);
    }

    /**
     * Redirect to response with reauthenticate path.
     *
     * @param  \Illuminate\Http\Request  $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    protected function invalidated($request)
    {
        return redirect(handles('orchestra::sudo'));
    }
}
