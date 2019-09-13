<?php

namespace Orchestra\Foundation\Support\Providers;

use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Contracts\Http\Kernel;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Routing\Router;
use Orchestra\Foundation\Support\Providers\Concerns\RouteProvider;
use Orchestra\Support\Providers\Concerns\EventProvider;
use Orchestra\Support\Providers\Concerns\MiddlewareProvider;
use Orchestra\Support\Providers\Concerns\PackageProvider;
use RuntimeException;

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
        $this->registerEventListeners($events);

        if ($this->app->bound(Kernel::class)) {
            $kernel = $this->app->make(Kernel::class);
            $router = $this->app->make(Router::class);

            $this->registerRouteMiddleware($router, $kernel);
            $this->bootExtensionRouting();
        }

        $this->bootExtensionComponents();
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
