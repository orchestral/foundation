<?php namespace Orchestra\Foundation\Filters;

use Illuminate\Http\Request;
use Illuminate\Routing\Route;
use Orchestra\Foundation\Kernel;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Http\RedirectResponse;
use Illuminate\Contracts\Config\Repository;

class ManageAuthorizationFilter
{
    /**
     * The application implementation.
     *
     * @var \Orchestra\Foundation\Kernel
     */
    protected $kernel;

    /**
     * The authenticator implementation.
     *
     * @var \Illuminate\Contracts\Auth\Guard
     */
    protected $auth;

    /**
     * The config repository implementation.
     *
     * @var \Illuminate\Contracts\Config\Repository
     */
    protected $config;

    /**
     * Create a new filter instance.
     *
     * @param  \Orchestra\Foundation\Kernel  $kernel
     * @param  \Illuminate\Contracts\Auth\Guard  $auth
     * @param  \Illuminate\Contracts\Config\Repository  $config
     */
    public function __construct(Kernel $kernel, Guard $auth, Repository $config)
    {
        $this->kernel = $kernel;
        $this->auth = $auth;
        $this->config = $config;
    }

    public function filter(Route $route, Request $request, $value = 'orchestra')
    {
        if (! $this->kernel->acl()->can("manage-{$value}")) {
            $type = ($this->auth->guest() ? 'guest' : 'user');
            $url  = $this->config->get("orchestra/foundation::routes.{$type}");

            return new RedirectResponse(handles($url));
        }
    }
}
