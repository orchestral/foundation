<?php namespace Orchestra\Foundation\Support\Providers;

use RuntimeException;
use Illuminate\Routing\Router;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

abstract class RouteServiceProvider extends ServiceProvider
{
    /**
     * The controller namespace for the application or extension.
     *
     * @var string|null
     */
    protected $namespace = 'app';

    /**
     * The fallback route prefix.
     *
     * @var string
     */
    protected $fallbackRoutePrefix = '/';

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

        $foundation->namespaced(
            $namespace,
            $this->buildRouteGeneratorCallback($path)
        );
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
        $attributes = [];

        if (! is_null($namespace)) {
            $attributes['namespace'] = $namespace;
        }

        $foundation->group(
            $this->namespace,
            $this->fallbackRoutePrefix,
            $attributes,
            $this->buildRouteGeneratorCallback($path)
        );
    }

    /**
     * Build route generator callback.
     *
     * @param  string  $path
     * @return \Closure
     */
    protected function buildRouteGeneratorCallback($path)
    {
        return function(Router $router) use ($path) {
            require $path;
        };
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
