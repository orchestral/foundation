<?php

namespace Orchestra\Foundation\Http\Middleware;

use Illuminate\Contracts\Encryption\Encrypter;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as BaseVerifier;
use Orchestra\Contracts\Foundation\Foundation;
use Orchestra\Http\Concerns\PassThrough;

class VerifyCsrfToken extends BaseVerifier
{
    use PassThrough;

    /**
     * Create a new middleware instance.
     *
     * @param  \Illuminate\Contracts\Foundation\Application  $app
     * @param  \Illuminate\Contracts\Encryption\Encrypter  $encrypter
     * @param  \Orchestra\Contracts\Foundation\Foundation  $foundation
     */
    public function __construct(Application $app, Encrypter $encrypter, Foundation $foundation)
    {
        $this->foundation = $foundation;

        parent::__construct($app, $encrypter);
    }

    /**
     * Determine if the request has a URI that should pass through CSRF verification.
     *
     * @param  \Illuminate\Http\Request  $request
     *
     * @return bool
     */
    protected function inExceptArray($request)
    {
        return $this->shouldPassThrough($request);
    }
}
