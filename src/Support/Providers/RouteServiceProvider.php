<?php namespace Orchestra\Foundation\Support\Providers;

use Illuminate\Routing\Router;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

abstract class RouteServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @param  \Illuminate\Routing\Router  $router
     * @return void
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

        $foundation->namespaced($namespace, function(Router $router) use ($path) {
            require $path;
        });
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
        $router = $this->app['router'];

        if (is_null($namespace)) {
            return require $path;
        }

        $router->group(['namespace' => $namespace], function(Router $router) use ($path) {
            require $path;
        });
    }

}
