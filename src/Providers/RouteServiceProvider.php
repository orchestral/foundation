<?php namespace Orchestra\Foundation\Providers;

use Illuminate\Routing\Router;
use Illuminate\Contracts\Http\Kernel;
use Illuminate\Contracts\Events\Dispatcher;
use Orchestra\Support\Providers\Traits\MiddlewareProviderTrait;
use Orchestra\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

class RouteServiceProvider extends ServiceProvider
{
    use MiddlewareProviderTrait;

    /**
     * The application's middleware stack.
     *
     * @var array
     */
    protected $middleware = [];

    /**
     * The application's route middleware.
     *
     * @var array
     */
    protected $routeMiddleware = [
        'orchestra.auth'        => 'Orchestra\Foundation\Http\Middleware\Authenticate',
        'orchestra.csrf'        => 'Orchestra\Foundation\Http\Middleware\VerifyCsrfToken',
        'orchestra.guest'       => 'Orchestra\Foundation\Http\Middleware\RedirectIfAuthenticated',
        'orchestra.installable' => 'Orchestra\Foundation\Http\Middleware\CanBeInstalled',
        'orchestra.installed'   => 'Orchestra\Foundation\Http\Middleware\RedirectIfInstalled',
        'orchestra.manage'      => 'Orchestra\Foundation\Http\Middleware\CanManage',
        'orchestra.registrable' => 'Orchestra\Foundation\Http\Middleware\CanRegisterUser',
    ];

    /**
     * Bootstrap the application events.
     *
     * @param  \Illuminate\Routing\Router  $router
     * @param  \Illuminate\Contracts\Http\Kernel  $kernel
     * @param  \Illuminate\Contracts\Events\Dispatcher  $events
     *
     * @return void
     */
    public function boot(Router $router, Kernel $kernel, Dispatcher $events)
    {
        $this->registerRouteMiddleware($router, $kernel);

        if ($this->app->routesAreCached()) {
            $this->loadCachedRoutes();
        } else {
            $this->loadRoutes();
        }

        $events->fire('orchestra.done');
    }

    /**
     * Define the routes for the application.
     *
     * @param  \Illuminate\Routing\Router  $router
     *
     * @return void
     */
    public function map(Router $router)
    {
        $path = realpath(__DIR__.'/../../');

        require "{$path}/src/routes.php";
    }
}
