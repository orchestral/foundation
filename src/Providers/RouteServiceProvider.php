<?php

namespace Orchestra\Foundation\Providers;

class RouteServiceProvider extends HttpServiceProvider
{
    /**
     * Load the application routes.
     *
     * @return void
     */
    protected function loadRoutes(): void
    {
        $path = \realpath(__DIR__.'/../../');

        require "{$path}/routes/web.php";
    }
}
