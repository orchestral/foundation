<?php

namespace Orchestra\Foundation\Providers;

use Illuminate\Support\AggregateServiceProvider;

class ConsoleSupportServiceProvider extends AggregateServiceProvider
{
    /**
     * The provider class names.
     *
     * @var array
     */
    protected $providers = [
        ArtisanServiceProvider::class,
        \Illuminate\Foundation\Providers\ComposerServiceProvider::class,
        \Orchestra\Database\ConsoleServiceProvider::class,
        \Illuminate\Database\MigrationServiceProvider::class,

        \Orchestra\Auth\CommandServiceProvider::class,
        \Orchestra\Extension\CommandServiceProvider::class,
        \Orchestra\Memory\CommandServiceProvider::class,
        \Orchestra\Foundation\Providers\CommandServiceProvider::class,
        \Orchestra\Publisher\CommandServiceProvider::class,
        \Orchestra\View\CommandServiceProvider::class,
    ];

    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = true;
}
