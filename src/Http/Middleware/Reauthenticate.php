<?php

namespace Orchestra\Foundation\Http\Middleware;

use Closure;
use Mpociot\Reauthenticate\Middleware\Reauthenticate;

class Reauthenticate extends Middleware
{
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
