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
    protected $events;

    /**
     * Create a new middleware instance.
     *
     * @param  \Illuminate\Contracts\Events\Dispatcher  $events
     */
    public function __construct(Dispatcher $events)
    {
        $this->events = $events;
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
    protected function beforeSendingThroughPipeline(): void
    {
        $this->events->dispatch('orchestra.started: admin');
        $this->events->dispatch('orchestra.ready: admin');
    }

    /**
     * After sending through pipeline.
     *
     * @return void
     */
    protected function afterSendingThroughPipeline(): void
    {
        $this->events->dispatch('orchestra.done: admin');
    }
}
