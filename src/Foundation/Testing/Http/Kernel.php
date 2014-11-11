<?php namespace Orchestra\Foundation\Testing\Http;

class Kernel extends \Orchestra\Testbench\Http\Kernel
{
    /**
     * The application's middleware stack.
     *
     * @var array
     */
    protected $bootstrappers = [
    ];

    /**
     * The application's middleware stack.
     *
     * @var array
     */
    protected $middleware = [
        'Illuminate\Cookie\Middleware\EncryptCookies',
        'Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse',
        'Illuminate\Session\Middleware\StartSession',
        'Illuminate\View\Middleware\ShareErrorsFromSession',
    ];
}
