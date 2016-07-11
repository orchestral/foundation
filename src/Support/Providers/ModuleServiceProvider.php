<?php

namespace Orchestra\Foundation\Support\Providers;

use RuntimeException;
use Illuminate\Routing\Router;
use Illuminate\Contracts\Http\Kernel;
use Illuminate\Contracts\Events\Dispatcher;
use Orchestra\Support\Providers\Traits\EventProvider;
use Orchestra\Support\Providers\Traits\PackageProvider;
use Orchestra\Support\Providers\Traits\MiddlewareProvider;
use Orchestra\Foundation\Support\Providers\Traits\RouteProvider;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

abstract class ModuleServiceProvider extends ServiceProvider
{
    use EventProvider, MiddlewareProvider, PackageProvider, RouteProvider;

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
     * The event handler mappings for the application.
     *
     * @var array
     */
    protected $listen = [];

    /**
     * The subscriber classes to register.
     *
     * @var array
     */
    protected $subscribe = [];

    /**
     * The application's or extension's middleware stack.
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
     * The application's or extension's route middleware.
     *
     * @var array
     */
    protected $routeMiddleware = [];

    /**
     * {@inheritdoc}
     */
    public function boot()
    {
        $events = $this->app->make(Dispatcher::class);
        $kernel = $this->app->make(Kernel::class);
        $router = $this->app->make(Router::class);

        $this->registerEventListeners($events);
        $this->registerRouteMiddleware($router, $kernel);

        $this->bootExtensionComponents();
        $this->bootExtensionRouting();
    }

    /**
     * Boot extension components.
     *
     * @return void
     */
    protected function bootExtensionComponents()
    {
        //
    }

    /**
     * Boot extension routing.
     *
     * @return void
     */
    protected function bootExtensionRouting()
    {
        if (! $this->app->routesAreCached()) {
            $this->afterExtensionLoaded(function () {
                $this->loadRoutes();
            });
        }
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
