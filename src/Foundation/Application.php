<?php namespace Orchestra\Foundation;

use Orchestra\Routing\RoutingServiceProvider;

class Application extends \Illuminate\Foundation\Application
{
    /**
     * {@inheritdoc}
     */
    protected function registerRoutingProvider()
    {
        $this->register(new RoutingServiceProvider($this));
    }
}
