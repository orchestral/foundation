<?php namespace Orchestra\Foundation;

use Illuminate\Support\ServiceProvider;

class ConsoleServiceProvider extends ServiceProvider
{
    /**
     * The provider class names.
     *
     * @var array
     */
    protected $providers = array(
        'Orchestra\Auth\CommandServiceProvider',
        'Orchestra\Debug\CommandServiceProvider',
        'Orchestra\Extension\CommandServiceProvider',
        'Orchestra\Memory\CommandServiceProvider',
    );

    /**
     * An array of the service provider instances.
     *
     * @var array
     */
    protected $instances = array();

    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var boolean
     */
    protected $defer = true;

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->instances = array();

        foreach ($this->providers as $provider) {
            $this->instances[] = $this->app->register($provider);
        }
    }
}
