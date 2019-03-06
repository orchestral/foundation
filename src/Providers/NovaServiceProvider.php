<?php

namespace Orchestra\Foundation\Providers;

class NovaServiceProvider extends MiddlewareServiceProvider
{
    /**
     * Boot routes for Orchestra Platform.
     *
     * @return void
     */
    protected function bootRoutes(): void
    {
        if (! $this->app->routesAreCached()) {
            $this->afterExtensionLoaded(function () {
                $this->loadRoutes();
            });
        }
    }

    /**
     * Load the application routes.
     *
     * @return void
     */
    protected function loadRoutes(): void
    {
        //
    }
}
