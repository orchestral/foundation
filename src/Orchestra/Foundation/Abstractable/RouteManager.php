<?php namespace Orchestra\Foundation\Abstractable;

use Illuminate\Support\NamespacedItemResolver;
use Orchestra\Extension\RouteGenerator;

abstract class RouteManager
{
    /**
     * Application instance.
     *
     * @var \Illuminate\Foundation\Application
     */
    protected $app = null;

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
    public function __construct($app)
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
     * @param  string   $name   Package name
     * @return array
     */
    public function locate($name)
    {
        $query = '';

        // split URI and query string, the route resolver should not worry
        // about provided query string.
        if (strpos($name, '?') !== false) {
            list($name, $query) = explode('?', $name, 2);
        }

        list($package, $route, $item) = with(new NamespacedItemResolver)->parseKey($name);

        ! empty($item) and $route = "{$route}.{$item}";

        // Prepare route valid, since we already extract package from route
        // we can re append query string to route value.
        empty($route) and $route = '';
        empty($query) or $route = "{$route}?{$query}";

        // If package is empty, we should consider that the route is using
        // app (or root path), it doesn't matter at this stage if app is
        // an extension or simply handling root path.
        if (empty($package)) {
            $package = "app";
        }

        return array($package, $route);
    }

    /**
     * Return route group dispatch for a package/app.
     *
     * @param  string   $name   Package name
     * @return string
     */
    public function group($name, $default, $group = array())
    {
        $route = $this->route($name, $default);

        return array_merge($group, array(
            'prefix' => $route->prefix(),
            'domain' => $route->domain(),
        ));
    }

    /**
     *  Return handles URL for a package/app.
     *
     * @param  string   $name   Package name
     * @return string
     */
    public function handles($path)
    {
        list($package, $route) = $this->locate($path);

        // Get the path from route configuration, and append route.
        $locate = $this->route($package)->to($route);
        empty($locate) and $locate = '/';

        if (starts_with($locate, 'http')) {
            return $locate;
        }

        return $this->app['url']->to($locate);
    }

    /**
     *  Return if handles URL match given string.
     *
     * @param  string   $name   Package name
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
}
