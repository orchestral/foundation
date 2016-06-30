<?php

namespace Orchestra\Foundation\Http\Middleware;

use Closure;
use Mpociot\Reauthenticate\Middleware\Reauthenticate as Middleware;

class Reauthenticate extends Middleware
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
        if ($this->validAuth($request->session())) {
            return $next($request);
        }

        $request->session()->set('url.intended', $request->url());

        return $this->redirectToReauthenticate();
    }

    /**
     * Redirect to response with reauthenticate path.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    protected function redirectToReauthenticate()
    {
        return redirect('orchestra/foundation::sudo');
    }
}
