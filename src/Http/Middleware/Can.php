<?php namespace Orchestra\Foundation\Http\Middleware; 

use Closure;
use Orchestra\Contracts\Auth\Guard;
use Illuminate\Contracts\Config\Repository;
use Orchestra\Contracts\Foundation\Foundation;

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
     * @param  \Orchestra\Contracts\Foundation\Foundation  $foundation
     * @param  \Orchestra\Contracts\Auth\Guard  $auth
     * @param  \Illuminate\Contracts\Config\Repository  $config
     */
    public function __construct(Foundation $foundation, Guard $auth, Repository $config)
    {
        $this->foundation = $foundation;
        $this->auth       = $auth;
        $this->config     = $config;
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
            $type = ($this->auth->guest() ? 'guest' : 'user');
            $url  = $this->config->get("orchestra/foundation::routes.{$type}");

            return new RedirectResponse($this->foundation->handles($url));
        }

        return $next($request);
    }

    /**
     * Check authorization.
     *
     * @param  string  $action
     * @return bool
     */
    protected function authorize($action = null)
    {
        if (empty($action)) {
            return false;
        }

        return $this->foundation->acl()->can($action);
    }
}
