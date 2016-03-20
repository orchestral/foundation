<?php

namespace Orchestra\Foundation\Support\Providers;

use Orchestra\Foundation\Support\Providers\Traits\RouteProvider;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

abstract class RouteServiceProvider extends ServiceProvider
{
    use RouteProvider;

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
}
