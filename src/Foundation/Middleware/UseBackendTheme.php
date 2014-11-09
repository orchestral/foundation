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
        $this->beforeHandle();

        $response = $next($request);

        $this->afterHandle();

        return $response;
    }

    /**
     * Before handle.
     *
     * @return void
     */
    protected function beforeHandle()
    {
        $this->dispatcher->fire('orchestra.started: admin');
        $this->dispatcher->fire('orchestra.ready: admin');
    }

    /**
     * After handle.
     *
     * @return void
     */
    protected function afterHandle()
    {
        $this->dispatcher->fire('orchestra.done: admin');
    }
}
