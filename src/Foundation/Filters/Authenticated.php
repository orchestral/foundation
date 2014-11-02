<?php namespace Orchestra\Foundation\Filters;

use Illuminate\Http\Request;
use Illuminate\Routing\Route;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Config\Repository;
use Illuminate\Contracts\Routing\ResponseFactory;

class Authenticated
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
     * The response factory implementation.
     *
     * @var \Illuminate\Contracts\Routing\ResponseFactory
     */
    protected $response;

    /**
     * Create a new filter instance.
     *
     * @param  \Illuminate\Contracts\Auth\Guard                 $auth
     * @param  \Illuminate\Contracts\Config\Repository          $config
     * @param  \Illuminate\Contracts\Routing\ResponseFactory    $response
     */
    public function __construct(Guard $auth, Repository $config, ResponseFactory $response)
    {
        $this->auth = $auth;
        $this->config = $config;
        $this->response = $response;
    }

    /**
     * Run the request filter.
     *
     * @param  \Illuminate\Routing\Route    $route
     * @param  \Illuminate\Http\Request     $request
     * @return mixed|null
     */
    public function filter(Route $route, Request $request)
    {
        if ($this->auth->guest()) {
            if ($request->ajax()) {
                return $this->response->make('Unauthorized', 401);
            } else {
                $url = $this->config->get('orchestra/foundation::routes.guest');

                return $this->response->redirectGuest(handles($url));
            }
        }
    }
}
