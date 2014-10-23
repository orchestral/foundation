<?php namespace Orchestra\Foundation\Testing\Http;

class Kernel extends \Orchestra\Testbench\Http\Kernel
{
    /**
     * The application's middleware stack.
     *
     * @var array
     */
    protected $middleware = [
        'Illuminate\Cookie\Middleware\EncryptCookies',
        'Illuminate\Cookie\Middleware\AddQueuedCookiesToRequest',
        'Illuminate\Session\Middleware\ReadSession',
        'Illuminate\Session\Middleware\WriteSession',
        'Illuminate\View\Middleware\ShareErrorsFromSession',
    ];
}
