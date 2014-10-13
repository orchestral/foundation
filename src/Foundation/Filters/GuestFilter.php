<?php namespace Orchestra\Foundation\Filters;

use Illuminate\Contracts\Auth\Guard;
use Illuminate\Http\RedirectResponse;
use Illuminate\Contracts\Config\Repository;

class GuestFilter
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
     * Create a new filter instance.
     *
     * @param  \Illuminate\Contracts\Auth\Guard         $auth
     * @param  \Illuminate\Contracts\Config\Repository  $config
     */
    public function __construct(Guard $auth, Repository $config)
    {
        $this->auth = $auth;
        $this->config = $config;
    }

    /**
     * Run the request filter.
     *
     * @return mixed
     */
    public function filter()
    {
        $url = $this->config->get('orchestra/foundation::routes.user');

        if ($this->auth->check()) {
            return new RedirectResponse(handles($url));
        }
    }
}
