<?php namespace Orchestra\Foundation;

use Illuminate\Events\EventServiceProvider;
use Orchestra\Routing\RoutingServiceProvider;
use Illuminate\Foundation\Application as Foundation;
use Orchestra\Contracts\Foundation\DeferrableServiceContainer;

class Application extends Foundation implements DeferrableServiceContainer
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
     * Get the application's deferred services.
     *
     * @return array
     */
    public function getDeferredServices()
    {
        return $this->deferredServices;
    }
}
