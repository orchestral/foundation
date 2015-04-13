<?php namespace Orchestra\Foundation\Http\Middleware;

use Closure;
use Orchestra\Contracts\Auth\Guard;
use Illuminate\Http\RedirectResponse;
use Orchestra\Contracts\Authorization\Authorization;

class LoginAs
{
    /**
     * The authorization implementation.
     *
     * @var \Orchestra\Contracts\Authorization\Authorization
     */
    protected $acl;

    /**
     * The authentication implementation.
     *
     * @var \Orchestra\Contracts\Auth\Guard
     */
    protected $auth;

    /**
     * Construct a new middleware.
     *
     * @param  \Orchestra\Contracts\Authorization\Authorization  $acl
     * @param  \Orchestra\Contracts\Auth\Guard  $auth
     */
    public function __construct(Authorization $acl, Guard $auth)
    {
        $this->acl  = $acl;
        $this->auth = $auth;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     *
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $as = $request->input('_as');

        if ($this->authorize() && ! is_null($as)) {
            $this->auth->loginUsingId($as);

            return new RedirectResponse($request->url());
        }

        return $next($request);
    }

    /**
     * Check authorization.
     *
     * @return bool
     */
    protected function authorize()
    {
        return $this->acl->can('manage orchestra');
    }
}
