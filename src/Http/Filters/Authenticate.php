<?php namespace Orchestra\Foundation\Http\Filters;

use Illuminate\Http\Request;
use Illuminate\Routing\Route;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Config\Repository;
use Orchestra\Contracts\Foundation\Foundation;
use Illuminate\Contracts\Routing\ResponseFactory;

class Authenticate
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
     * The response factory implementation.
     *
     * @var \Illuminate\Contracts\Routing\ResponseFactory
     */
    protected $response;

    /**
     * Create a new filter instance.
     *
     * @param  \Orchestra\Contracts\Foundation\Foundation  $foundation
     * @param  \Illuminate\Contracts\Auth\Guard  $auth
     * @param  \Illuminate\Contracts\Config\Repository  $config
     * @param  \Illuminate\Contracts\Routing\ResponseFactory  $response
     */
    public function __construct(Foundation $foundation, Guard $auth, Repository $config, ResponseFactory $response)
    {
        $this->foundation = $foundation;
        $this->auth       = $auth;
        $this->config     = $config;
        $this->response   = $response;
    }

    /**
     * Run the request filter.
     *
     * @param  \Illuminate\Routing\Route  $route
     * @param  \Illuminate\Http\Request  $request
     *
     * @return mixed|null
     */
    public function filter(Route $route, Request $request)
    {
        if ($this->auth->guest()) {
            if ($request->ajax()) {
                return $this->response->make('Unauthorized', 401);
            } else {
                $url = $this->config->get('orchestra/foundation::routes.guest');

                return $this->response->redirectGuest($this->foundation->handles($url));
            }
        }
    }
}
