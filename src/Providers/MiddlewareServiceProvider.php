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
use Orchestra\Support\Providers\Concerns\MiddlewareProvider;
use Orchestra\Foundation\Http\Middleware\RedirectIfInstalled;
use Orchestra\Foundation\Http\Middleware\RedirectIfAuthenticated;
use Orchestra\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

abstract class MiddlewareServiceProvider extends ServiceProvider
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
        'orchestra.auth' => Authenticate::class,
        'orchestra.can' => Can::class,
        'orchestra.csrf' => RequireCsrfToken::class,
        'orchestra.guest' => RedirectIfAuthenticated::class,
        'orchestra.installable' => CanBeInstalled::class,
        'orchestra.installed' => RedirectIfInstalled::class,
        'orchestra.registrable' => CanRegisterUser::class,
        'orchestra.sudo' => Reauthenticate::class,
    ];

    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerRouteMiddleware(
            $this->app->make(Router::class), $this->app->make(Kernel::class)
        );

        $this->bootRoutes();

        $this->app->make('events')->dispatch('orchestra.ready');
    }

    /**
     * Boot routes for Orchestra Platform.
     *
     * @return void
     */
    abstract protected function bootRoutes(): void;
}
