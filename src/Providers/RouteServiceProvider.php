<?php

namespace Orchestra\Foundation\Providers;

use Illuminate\Routing\Router;
use Illuminate\Contracts\Http\Kernel;
use Orchestra\Foundation\Http\Middleware\Can;
use Orchestra\Http\Middleware\RequireCsrfToken;
use Orchestra\Foundation\Http\Middleware\Authenticate;
use Orchestra\Foundation\Http\Middleware\CanBeInstalled;
use Orchestra\Foundation\Http\Middleware\Reauthenticate;
use Orchestra\Foundation\Http\Middleware\CanRegisterUser;
use Orchestra\Support\Providers\Traits\MiddlewareProvider;
use Orchestra\Foundation\Http\Middleware\RedirectIfInstalled;
use Orchestra\Foundation\Http\Middleware\RedirectIfAuthenticated;
use Orchestra\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

class RouteServiceProvider extends ServiceProvider
{
    use MiddlewareProvider;

    /**
     * The application's middleware stack.
     *
     * @var array
     */
    protected $middleware = [];

    /**
     * The application's route middleware groups.
     *
     * @var array
     */
    protected $middlewareGroups = [];

    /**
     * The application's route middleware.
     *
     * @var array
     */
    protected $routeMiddleware = [
        'orchestra.auth'        => Authenticate::class,
        'orchestra.can'         => Can::class,
        'orchestra.csrf'        => RequireCsrfToken::class,
        'orchestra.guest'       => RedirectIfAuthenticated::class,
        'orchestra.installable' => CanBeInstalled::class,
        'orchestra.installed'   => RedirectIfInstalled::class,
        'orchestra.reauth'      => Reauthenticate::class,
        'orchestra.registrable' => CanRegisterUser::class,
    ];

    /**
     * Bootstrap the application events.
     *
     * @param  \Illuminate\Routing\Router  $router
     *
     * @return void
     */
    public function boot(Router $router)
    {
        $kernel = $this->app->make(Kernel::class);

        $this->registerRouteMiddleware($router, $kernel);

        if (! $this->app->routesAreCached()) {
            $this->afterExtensionLoaded(function () {
                $this->loadRoutes();
            });
        }

        $this->app->make('events')->fire('orchestra.ready');
    }

    /**
     * Load the application routes.
     *
     * @return void
     */
    protected function loadRoutes()
    {
        $path = realpath(__DIR__.'/../');

        require "{$path}/Http/routes.php";
    }
}
