<?php

namespace Orchestra\Foundation\Providers;

use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Support\AggregateServiceProvider;

class ConsoleSupportServiceProvider extends AggregateServiceProvider implements DeferrableProvider
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

        \Orchestra\Extension\CommandServiceProvider::class,
        \Orchestra\Foundation\Providers\CommandServiceProvider::class,
        \Orchestra\Publisher\CommandServiceProvider::class,
        \Orchestra\View\CommandServiceProvider::class,
    ];
}
