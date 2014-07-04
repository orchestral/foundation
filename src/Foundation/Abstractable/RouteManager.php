<?php namespace Orchestra\Foundation\Abstractable;

use Closure;
use Illuminate\Foundation\Application;
use Illuminate\Support\NamespacedItemResolver;
use Orchestra\Extension\RouteGenerator;

abstract class RouteManager
{
    /**
     * Application instance.
     *
     * @var \Illuminate\Foundation\Application
     */
    protected $app;

    /**
     * List of routes.
     *
     * @var array
     */
    protected $routes = array();

    /**
     * Construct a new instance.
     *
     * @param  \Illuminate\Foundation\Application   $app
     * @return void
     */
    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    /**
     * Start the application.
     *
     * @return object
     */
    abstract public function boot();

    /**
     *  Return locate handles configuration for a package/app.
     *
     * @param  string   $path
     * @return array
     */
    public function locate($path)
    {
        $query = '';

        // split URI and query string, the route resolver should not worry
        // about provided query string.
        if (strpos($path, '?') !== false) {
            list($path, $query) = explode('?', $path, 2);
        }

        list($package, $route, $item) = with(new NamespacedItemResolver)->parseKey($path);

        ! empty($item) && $route = "{$route}.{$item}";

        // Prepare route valid, since we already extract package from route
        // we can re append query string to route value.
        empty($route) && $route = '';
        empty($query) || $route = "{$route}?{$query}";

        // If package is empty, we should consider that the route is using
        // app (or root path), it doesn't matter at this stage if app is
        // an extension or simply handling root path.
        empty($package) && $package = "app";

        return array($package, $route);
    }

    /**
     * Return route group dispatch for a package/app.
     *
     * @param  string           $name
     * @param  string           $default
     * @param  array            $group
     * @param  \Closure|null    $callback
     * @return array
     */
    public function group($name, $default, $group = array(), Closure $callback = null)
    {
        $route = $this->route($name, $default);

        $group = array_merge($group, array(
            'prefix' => $route->prefix(),
            'domain' => $route->domain(),
        ));

        if (is_callable($callback)) {
            $this->app['router']->group($group, $callback);
        }

        return $group;
    }

    /**
     *  Return handles URL for a package/app.
     *
     * @param  string   $path
     * @return string
     */
    public function handles($path)
    {
        list($package, $route) = $this->locate($path);

        // Get the path from route configuration, and append route.
        $locate = $this->route($package)->to($route);
        empty($locate) && $locate = '/';

        if (starts_with($locate, 'http')) {
            return $locate;
        }

        return $this->app['url']->to($locate);
    }

    /**
     *  Return if handles URL match given string.
     *
     * @param  string   $path
     * @return boolean
     */
    public function is($path)
    {
        list($package, $route) = $this->locate($path);

        return $this->route($package)->is($route);
    }

    /**
     * Get extension route.
     *
     * @param  string   $name
     * @param  string   $default
     * @return string
     */
    public function route($name, $default = '/')
    {
        // Boot the application.
        $this->boot();

        if (isset($this->routes[$name])) {
            return $this->routes[$name];
        }

        $route = null;

        // Orchestra Platform routing is managed by `orchestra/foundation::handles`
        // and can be manage using configuration.
        if (! in_array($name, array('orchestra', 'orchestra/foundation'))) {
            $route = $this->app['orchestra.extension']->route($name, $default);
        } else {
            $name  = 'orchestra';
            $route = new RouteGenerator(
                $this->app['config']->get('orchestra/foundation::handles', $default),
                $this->app['request']
            );
        }

        return $this->routes[$name] = $route;
    }

    /**
     * Run the callback when route is matched.
     *
     * @param  string   $path
     * @param  mixed    $listener
     * @return void
     */
    public function when($path, $listener)
    {
        $me       = $this;
        $listener = $this->app['events']->makeListener($listener);

        $this->app->booted(function () use ($listener, $me, $path) {
            if ($me->is($path)) {
                call_user_func($listener);
            }
        });
    }
}
