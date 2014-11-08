<?php namespace Orchestra\Foundation\Middleware;

use Closure;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Contracts\Routing\Middleware;

class UseBackendTheme implements Middleware
{
    /**
     * The event dispatcher implementation.
     *
     * @var \Illuminate\Contracts\Events\Dispatcher
     */
    protected $dispatcher;

    /**
     * Create a new middleware instance.
     *
     * @param  \Illuminate\Contracts\Events\Dispatcher   $dispatcher
     */
    public function __construct(Dispatcher $dispatcher)
    {
        $this->dispatcher = $dispatcher;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request   $request
     * @param  \Closure   $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $this->dispatcher->fire('orchestra.started: admin');
        $this->dispatcher->fire('orchestra.ready: admin');

        $response = $next($request);

        $this->dispatcher->fire('orchestra.done: admin');

        return $response;
    }
}
