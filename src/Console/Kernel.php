<?php namespace Orchestra\Foundation\Console;

use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The bootstrap classes for the application.
     *
     * @return void
     */
    protected $bootstrappers = [
        'Illuminate\Foundation\Bootstrap\DetectEnvironment',
        'Orchestra\Config\Bootstrap\LoadConfiguration',
        'Illuminate\Foundation\Bootstrap\ConfigureLogging',
        'Illuminate\Foundation\Bootstrap\HandleExceptions',
        'Illuminate\Foundation\Bootstrap\RegisterFacades',
        'Illuminate\Foundation\Bootstrap\SetRequestForConsole',
        'Orchestra\Foundation\Bootstrap\UserAccessPolicy',
        'Illuminate\Foundation\Bootstrap\RegisterProviders',
        'Illuminate\Foundation\Bootstrap\BootProviders',

        'Orchestra\Foundation\Bootstrap\LoadFoundation',
        'Orchestra\Extension\Bootstrap\LoadExtension',
        'Orchestra\Foundation\Bootstrap\LoadUserMetaData',
        'Orchestra\View\Bootstrap\LoadCurrentTheme',
        'Orchestra\Foundation\Bootstrap\LoadExpresso',
    ];
}
