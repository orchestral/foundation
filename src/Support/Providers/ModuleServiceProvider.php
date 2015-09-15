<?php namespace Orchestra\Foundation\Support\Providers;

use RuntimeException;
use Illuminate\Routing\Router;
use Illuminate\Contracts\Http\Kernel;
use Illuminate\Contracts\Events\Dispatcher;
use Orchestra\Support\Providers\Traits\EventProviderTrait;
use Orchestra\Support\Providers\Traits\PackageProviderTrait;
use Orchestra\Support\Providers\Traits\MiddlewareProviderTrait;
use Orchestra\Foundation\Support\Providers\Traits\RouteProviderTrait;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

abstract class ModuleServiceProvider extends ServiceProvider
{
    use EventProviderTrait, MiddlewareProviderTrait, PackageProviderTrait, RouteProviderTrait;

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
     * The application's or extension's route middleware.
     *
     * @var array
     */
    protected $routeMiddleware = [];

    /**
     * {@inheritdoc}
     */
    public function boot(Router $router)
    {
        $events = $this->app->make(Dispatcher::class);
        $kernel = $this->app->make(Kernel::class);

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
            $this->loadRoutes();
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
