<?php namespace Orchestra\Foundation;

use Illuminate\Support\AggregateServiceProvider;

class ConsoleSupportServiceProvider extends AggregateServiceProvider
{
    /**
     * The provider class names.
     *
     * @var array
     */
    protected $providers = array(
        'Orchestra\Auth\CommandServiceProvider',
        'Orchestra\Extension\CommandServiceProvider',
        'Orchestra\Memory\CommandServiceProvider',
        'Orchestra\Optimize\OptimizeServiceProvider',
        'Orchestra\View\CommandServiceProvider',
    );

    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var boolean
     */
    protected $defer = true;
}
