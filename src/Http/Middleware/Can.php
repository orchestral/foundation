<?php

namespace Orchestra\Foundation\Http\Middleware;

use Closure;
use Orchestra\Contracts\Auth\Guard;
use Illuminate\Contracts\Config\Repository;
use Orchestra\Contracts\Foundation\Foundation;
use Illuminate\Contracts\Routing\ResponseFactory;

class Can
{
    /**
     * The application implementation.
     *
     * @var \Orchestra\Contracts\Foundation\Foundation
     */
    protected $foundation;

    /**
     * The authenticator implementation.
     *
     * @var \Orchestra\Contracts\Auth\Guard
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
     * @param  \Orchestra\Contracts\Foundation\Foundation  $foundation
     * @param  \Orchestra\Contracts\Auth\Guard  $auth
     * @param  \Illuminate\Contracts\Config\Repository  $config
     * @param  \Illuminate\Contracts\Routing\ResponseFactory  $response
     */
    public function __construct(Foundation $foundation, Guard $auth, Repository $config, ResponseFactory $response)
    {
        $this->foundation = $foundation;
        $this->auth = $auth;
        $this->config = $config;
        $this->response = $response;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $action
     *
     * @return mixed
     */
    public function handle($request, Closure $next, $action = null)
    {
        if (! $this->authorize($action)) {
            return $this->responseOnUnauthorized($request);
        }

        return $next($request);
    }

    /**
     * Check authorization.
     *
     * @param  string  $action
     *
     * @return bool
     */
    protected function authorize($action = null)
    {
        if (empty($action)) {
            return false;
        }

        return $this->foundation->acl()->can($action);
    }

    /**
     * Response on authorized request.
     *
     * @param  \Illuminate\Http\Request  $request
     *
     * @return mixed
     */
    protected function responseOnUnauthorized($request)
    {
        if ($request->ajax()) {
            return $this->response->make('Unauthorized', 401);
        }

        $type = ($this->auth->guest() ? 'guest' : 'user');
        $url = $this->config->get("orchestra/foundation::routes.{$type}");

        return $this->response->redirectTo($this->foundation->handles($url));
    }
}
