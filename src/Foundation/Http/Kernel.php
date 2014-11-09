<?php namespace Orchestra\Foundation\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel
{
    /**
     * The bootstrap classes for the application.
     *
     * @return void
     */
    protected $bootstrappers = [
        'Illuminate\Foundation\Bootstrap\DetectEnvironment',
        'Illuminate\Foundation\Bootstrap\LoadConfiguration',
        'Illuminate\Foundation\Bootstrap\ConfigureLogging',
        'Illuminate\Foundation\Bootstrap\HandleExceptions',
        'Illuminate\Foundation\Bootstrap\RegisterFacades',
        'Illuminate\Foundation\Bootstrap\RegisterProviders',
        'Illuminate\Foundation\Bootstrap\BootProviders',

        'Orchestra\Foundation\Bootstrap\UserAccessPolicy',
        'Orchestra\Extension\Bootstrap\LoadExtension',
        'Orchestra\Foundation\Bootstrap\LoadUserMetaData',
        'Orchestra\Foundation\Bootstrap\NotifyIfSafeMode',
        'Orchestra\View\Bootstrap\LoadCurrentTheme',
        'Orchestra\Foundation\Bootstrap\LoadExpresso',
    ];
}
