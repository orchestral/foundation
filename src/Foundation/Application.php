<?php namespace Orchestra\Foundation;

use Illuminate\Http\Request;
use Illuminate\Events\EventServiceProvider;
use Orchestra\Routing\RoutingServiceProvider;
use Illuminate\Foundation\Application as BaseApplication;
use Orchestra\Contracts\Foundation\DeferrableServiceContainer;

class Application extends BaseApplication implements DeferrableServiceContainer
{
    /**
     * Register all of the base service providers.
     *
     * @return void
     */
    protected function registerBaseServiceProviders()
    {
        $this->register(new EventServiceProvider($this));

        $this->register(new RoutingServiceProvider($this));
    }

    /**
     * Run the given array of bootstrap classes.
     *
     * @param  array  $bootstrappers
     * @return void
     */
    public function bootstrapWith(array $bootstrappers)
    {
        if ($this->runningInConsole() && ! $this->bound('request')) {
            $this->setRequestForConsoleEnvironment();
        }

        parent::bootstrapWith($bootstrappers);
    }

    /**
     * Get the application's deferred services.
     *
     * @return array
     */
    public function getDeferredServices()
    {
        return $this->deferredServices;
    }

    /**
     * Flush the container of all bindings and resolved instances.
     *
     * @return void
     */
    public function flush()
    {
        parent::flush();

        $this->hasBeenBootstrapped = false;
    }

    /**
     * Set the application request for the console environment.
     *
     * @return void
     */
    public function setRequestForConsoleEnvironment()
    {
        $url = $this['config']->get('app.url', 'http://localhost');

        $this->instance('request', Request::create($url, 'GET', [], [], [], $_SERVER));
    }
}
