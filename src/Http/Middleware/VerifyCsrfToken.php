<?php

namespace Orchestra\Foundation\Http\Middleware;

use Illuminate\Foundation\Application;
use Orchestra\Http\Traits\PassThrough;
use Illuminate\Contracts\Encryption\Encrypter;
use Orchestra\Contracts\Foundation\Foundation;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as BaseVerifier;

class VerifyCsrfToken extends BaseVerifier
{
    use PassThrough;

    /**
     * Create a new middleware instance.
     *
     * @param  \Illuminate\Foundation\Application  $app
     * @param  \Illuminate\Contracts\Encryption\Encrypter  $encrypter
     * @param  \Orchestra\Contracts\Foundation\Foundation  $foundation
     */
    public function __construct(Application $app, Encrypter $encrypter, Foundation $foundation)
    {
        $this->foundation = $foundation;

        parent::__construct($app, $encrypter);
    }
}
