<?php

namespace Orchestra\Foundation\Support\Providers\Traits;

use Illuminate\Routing\Router;

trait RouteProvider
{
    /**
     * Load the backend routes file for the application.
     *
     * @param  string  $path
     * @param  string|null  $namespace
     * @param  array  $attributes
     *
     * @return void
     */
    protected function loadBackendRoutesFrom($path, $namespace = '', array $attributes = [])
    {
        $foundation = $this->app->make('orchestra.app');
        $attributes = $this->resolveRouteGroupAttributes($namespace, $attributes);

        $foundation->namespaced(null, $attributes, $this->getRouteLoader($path));
    }

    /**
     * Load the frontend routes file for the application.
     *
     * @param  string  $path
     * @param  string|null  $namespace
     *
     * @return void
     */
    protected function loadFrontendRoutesFrom($path, $namespace = '', array $attributes = [])
    {
        $foundation = $this->app->make('orchestra.app');
        $attributes = $this->resolveRouteGroupAttributes($namespace, $attributes);

        $foundation->group($this->routeGroup, $this->routePrefix, $attributes, $this->getRouteLoader($path));
    }

    /**
     * Build route generator callback.
     *
     * @param  string  $path
     *
     * @return \Closure
     */
    protected function getRouteLoader($path)
    {
        return function (Router $router) use ($path) {
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
        $this->app->make('orchestra.extension')->after($callback);
    }

    /**
     * Resolve route group attributes.
     *
     * @param  string|null  $namespace
     * @param  array  $attributes
     *
     * @return array
     */
    protected function resolveRouteGroupAttributes($namespace = null, array $attributes = [])
    {
        if (! is_null($namespace)) {
            $attributes['namespace'] = empty($namespace) ? $this->namespace : "{$this->namespace}\{$namespace}";
        }

        return $attributes;
    }
}
