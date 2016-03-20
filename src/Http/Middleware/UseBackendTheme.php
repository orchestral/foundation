<?php

namespace Orchestra\Foundation\Http\Middleware;

use Closure;
use Illuminate\Contracts\Events\Dispatcher;

class UseBackendTheme
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
     * @param  \Illuminate\Contracts\Events\Dispatcher  $dispatcher
     */
    public function __construct(Dispatcher $dispatcher)
    {
        $this->dispatcher = $dispatcher;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     *
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $this->beforeSendingThroughPipeline();

        $response = $next($request);

        $this->afterSendingThroughPipeline();

        return $response;
    }

    /**
     * Before sending through pipeline.
     *
     * @return void
     */
    protected function beforeSendingThroughPipeline()
    {
        $this->dispatcher->fire('orchestra.started: admin');
        $this->dispatcher->fire('orchestra.ready: admin');
    }

    /**
     * After sending through pipeline.
     *
     * @return void
     */
    protected function afterSendingThroughPipeline()
    {
        $this->dispatcher->fire('orchestra.done: admin');
    }
}
