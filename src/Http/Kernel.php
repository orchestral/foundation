<?php

namespace Orchestra\Foundation\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel
{
    /**
     * The bootstrap classes for the application.
     *
     * @return void
     */
    protected $bootstrappers = [
        \Illuminate\Foundation\Bootstrap\DetectEnvironment::class,
        \Orchestra\Config\Bootstrap\LoadConfiguration::class,
        \Illuminate\Foundation\Bootstrap\ConfigureLogging::class,
        \Illuminate\Foundation\Bootstrap\HandleExceptions::class,
        \Illuminate\Foundation\Bootstrap\RegisterFacades::class,
        \Illuminate\Foundation\Bootstrap\RegisterProviders::class,
        \Illuminate\Foundation\Bootstrap\BootProviders::class,

        \Orchestra\Foundation\Bootstrap\LoadFoundation::class,
        \Orchestra\Foundation\Bootstrap\UserAccessPolicy::class,
        \Orchestra\Extension\Bootstrap\LoadExtension::class,
        \Orchestra\Foundation\Bootstrap\LoadUserMetaData::class,
        \Orchestra\Foundation\Bootstrap\NotifyIfSafeMode::class,
        \Orchestra\View\Bootstrap\LoadCurrentTheme::class,
        \Orchestra\Foundation\Bootstrap\LoadExpresso::class,
    ];
}
