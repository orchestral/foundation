<?php namespace Orchestra\Foundation\Support\Providers;

use RuntimeException;
use Illuminate\Routing\Router;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

abstract class RouteServiceProvider extends ServiceProvider
{
    /**
     * The application or extension namespace.
     *
     * @var string|null
     */
    protected $namespace;

    /**
     * The application or extension group namespace.
     *
     * @var string|null
     */
    protected $routeGroup = 'app';

    /**
     * The fallback route prefix.
     *
     * @var string
     */
    protected $routePrefix = '/';

    /**
     * {@inheritdoc}
     */
    public function boot(Router $router)
    {
        if (! $this->app->routesAreCached()) {
            $this->loadRoutes();
        }
    }

    /**
     * Load the backend routes file for the application.
     *
     * @param  string  $path
     * @param  string|null  $namespace
     * @return void
     */
    protected function loadBackendRoutesFrom($path, $namespace = null)
    {
        $foundation = $this->app['orchestra.app'];
        $namespace  = $namespace ?: $this->namespace;

        $foundation->namespaced($namespace, $this->getRouteLoader($path));
    }

    /**
     * Load the frontend routes file for the application.
     *
     * @param  string  $path
     * @param  string|null  $namespace
     * @return void
     */
    protected function loadFrontendRoutesFrom($path, $namespace = null)
    {
        $foundation = $this->app['orchestra.app'];
        $namespace  = $namespace ?: $this->namespace;
        $attributes = [];

        if (! is_null($namespace)) {
            $attributes['namespace'] = $namespace;
        }

        $foundation->group($this->routeGroup, $this->routePrefix, $attributes, $this->getRouteLoader($path));
    }

    /**
     * Build route generator callback.
     *
     * @param  string  $path
     * @return \Closure
     */
    protected function getRouteLoader($path)
    {
        return function(Router $router) use ($path) {
            require $path;
        };
    }

    /**
     * Create an event listener for `orchestra.extension: booted` to allow
     * application to be loaded only after extension routing.
     *
     * @param  \Closure|string  $callback
     */
    protected function afterExtensionLoaded($callback)
    {
        $this->app['events']->listen('orchestra.extension: booted', $callback);
    }

    /**
     * {@inheritdoc}
     */
    protected function loadCachedRoutes()
    {
        throw new RuntimeException('loadCachedRoutes() method is not supported.');
    }

    /**
     * {@inheritdoc}
     */
    protected function setRootControllerNamespace()
    {
        throw new RuntimeException('setRootControllerNamespace() method is not supported.');
    }
}
