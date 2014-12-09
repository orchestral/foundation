<?php namespace Orchestra\Foundation\Providers;

use Illuminate\Support\AggregateServiceProvider;

class ConsoleSupportServiceProvider extends AggregateServiceProvider
{
    /**
     * The provider class names.
     *
     * @var array
     */
    protected $providers = [
        'Illuminate\Auth\GeneratorServiceProvider',
        'Illuminate\Console\ScheduleServiceProvider',
        'Illuminate\Database\MigrationServiceProvider',
        'Illuminate\Database\SeedServiceProvider',
        'Illuminate\Foundation\Providers\ComposerServiceProvider',
        'Illuminate\Queue\FailConsoleServiceProvider',
        'Illuminate\Routing\GeneratorServiceProvider',
        'Illuminate\Session\CommandsServiceProvider',

        'Orchestra\Auth\CommandServiceProvider',
        'Orchestra\Extension\CommandServiceProvider',
        'Orchestra\Memory\CommandServiceProvider',
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
