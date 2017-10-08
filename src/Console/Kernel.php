<?php

namespace Orchestra\Foundation\Console;

use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The bootstrap classes for the application.
     *
     * @return void
     */
    protected $bootstrappers = [
        \Illuminate\Foundation\Bootstrap\LoadEnvironmentVariables::class,
        \Orchestra\Config\Bootstrap\LoadConfiguration::class,
        \Illuminate\Foundation\Bootstrap\HandleExceptions::class,
        \Illuminate\Foundation\Bootstrap\RegisterFacades::class,
        \Illuminate\Foundation\Bootstrap\SetRequestForConsole::class,
        \Illuminate\Foundation\Bootstrap\RegisterProviders::class,
        \Illuminate\Foundation\Bootstrap\BootProviders::class,

        \Orchestra\Foundation\Bootstrap\LoadAuthen::class,
        \Orchestra\Foundation\Bootstrap\LoadFoundation::class,
        \Orchestra\Foundation\Bootstrap\UserAccessPolicy::class,
        \Orchestra\Extension\Bootstrap\LoadExtension::class,
        \Orchestra\Foundation\Bootstrap\LoadUserMetaData::class,
        \Orchestra\View\Bootstrap\LoadCurrentTheme::class,
        \Orchestra\Foundation\Bootstrap\LoadExpresso::class,
    ];
}
