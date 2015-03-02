<?php namespace Orchestra\Foundation\Filters;

use Illuminate\Contracts\Auth\Guard;
use Illuminate\Http\RedirectResponse;
use Illuminate\Contracts\Config\Repository;
use Orchestra\Contracts\Foundation\Foundation;

class IsGuest
{
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
     * The application implementation.
     *
     * @var \Orchestra\Contracts\Foundation\Foundation
     */
    protected $foundation;

    /**
     * Create a new filter instance.
     *
     * @param  \Orchestra\Contracts\Foundation\Foundation  $foundation
     * @param  \Illuminate\Contracts\Auth\Guard         $auth
     * @param  \Illuminate\Contracts\Config\Repository  $config
     */
    public function __construct(Foundation $foundation, Guard $auth, Repository $config)
    {
        $this->foundation = $foundation;
        $this->auth       = $auth;
        $this->config     = $config;
    }

    /**
     * Run the request filter.
     *
     * @return mixed
     */
    public function filter()
    {
        if ($this->auth->check()) {
            $url = $this->config->get('orchestra/foundation::routes.user');

            return new RedirectResponse($this->foundation->handles($url));
        }
    }
}
