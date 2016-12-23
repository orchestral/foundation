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
        'Orchestra\Foundation\Providers\ArtisanServiceProvider',
        'Orchestra\Database\MigrationServiceProvider',
        'Illuminate\Database\SeedServiceProvider',
        'Illuminate\Foundation\Providers\ComposerServiceProvider',
        'Illuminate\Queue\ConsoleServiceProvider',

        'Orchestra\Auth\CommandServiceProvider',
        'Orchestra\Extension\CommandServiceProvider',
        'Orchestra\Memory\CommandServiceProvider',
        'Orchestra\Foundation\Providers\CommandServiceProvider',
        'Orchestra\Optimize\OptimizeServiceProvider',
        'Orchestra\Publisher\CommandServiceProvider',
        'Orchestra\View\CommandServiceProvider',
    ];

    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = true;
}
