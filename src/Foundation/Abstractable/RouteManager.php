<?php namespace Orchestra\Foundation\Abstractable;

use Closure;
use Illuminate\Foundation\Application;
use Illuminate\Support\Arr;
use Illuminate\Support\NamespacedItemResolver;
use Orchestra\Extension\RouteGenerator;
use Orchestra\Support\Str;

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
     * @param  array    $options
     * @return array
     */
    public function locate($path, array $options = array())
    {
        $query = '';

        // split URI and query string, the route resolver should not worry
        // about provided query string.
        if (strpos($path, '?') !== false) {
            list($path, $query) = explode('?', $path, 2);
        }

        list($package, $route, $item) = with(new NamespacedItemResolver)->parseKey($path);

        $route = $this->prepareValidRoute($route, $item, $query, $options);


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
     * @param  array            $attributes
     * @param  \Closure|null    $callback
     * @return array
     */
    public function group($name, $default, $attributes = array(), Closure $callback = null)
    {
        $route = $this->route($name, $default);

        if ($attributes instanceof Closure) {
            $callback   = $attributes;
            $attributes = array();
        }

        $attributes = array_merge($attributes, array(
            'prefix' => $route->prefix(),
            'domain' => $route->domain(),
        ));

        if (is_callable($callback)) {
            $this->app['router']->group($attributes, $callback);
        }

        return $attributes;
    }

    /**
     *  Return handles URL for a package/app.
     *
     * @param  string   $path
     * @param  array    $options
     * @return string
     */
    public function handles($path, array $options = array())
    {
        list($package, $route) = $this->locate($path, $options);

        // Get the path from route configuration, and append route.
        $locate = $this->route($package)->to($route);
        empty($locate) && $locate = '/';

        if (Str::startsWith($locate, 'http')) {
            return $locate;
        }

        return $this->app['url']->to($locate);
    }

    /**
     *  Return if handles URL match given string.
     *
     * @param  string   $path
     * @return bool
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
     * @return \Orchestra\Extension\RouteGenerator
     */
    public function route($name, $default = '/')
    {
        // Boot the application.
        $this->boot();

        if (in_array($name, array('orchestra', 'orchestra/foundation'))) {
            $name = 'orchestra';
        }

        if (! isset($this->routes[$name])) {
            $this->routes[$name] = $this->generateRouteByName($name, $default);
        }

        return $this->routes[$name];
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

    /**
     * Generte route by name.
     *
     * @param  string   $name
     * @param  string   $default
     * @return \Orchestra\Extension\RouteGenerator
     */
    protected function generateRouteByName($name, $default)
    {
        // Orchestra Platform routing is managed by `orchestra/foundation::handles`
        // and can be manage using configuration.
        if (in_array($name, array('orchestra'))) {
            return new RouteGenerator(
                $this->app['config']->get('orchestra/foundation::handles', $default),
                $this->app['request']
            );
        }

        return $this->app['orchestra.extension']->route($name, $default);
    }

    /**
     * Prepare valid route, since we already extract package from route
     * we can re-append query string to route value.
     *
     * @param  string  $route
     * @param  string  $item
     * @param  string  $query
     * @param  array   $options
     * @return string
     */
    protected function prepareValidRoute($route, $item, $query, array $options)
    {
        if (!! Arr::get($options, 'csrf', false)) {
            $query .= (! empty($query) ? "&" : "")."_token=".csrf_token();
        }

        ! empty($item) && $route = "{$route}.{$item}";
        empty($route) && $route = '';
        empty($query) || $route = "{$route}?{$query}";

        return $route;
    }
}
