<?php namespace Orchestra\Foundation\Filters;

use Illuminate\Http\Request;
use Illuminate\Routing\Route;
use Illuminate\Session\TokenMismatchException;
use Symfony\Component\Security\Core\Util\StringUtils;

class VerifyCsrfToken
{
    /**
     * Run the request filter.
     *
     * @param  \Illuminate\Routing\Route  $route
     * @param  \Illuminate\Http\Request  $request
     * @return mixed
     * @throws \Illuminate\Session\TokenMismatchException
    */
    public function filter(Route $route, Request $request)
    {
        if (! StringUtils::equals($request->getSession()->token(), $request->input('_token'))) {
            throw new TokenMismatchException;
        }
    }
}
