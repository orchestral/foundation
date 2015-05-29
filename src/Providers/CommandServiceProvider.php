<?php namespace Orchestra\Foundation\Providers;

use Orchestra\Foundation\Console\RefreshCommand;
use Orchestra\Support\Providers\CommandServiceProvider as ServiceProvider;

class CommandServiceProvider extends ServiceProvider
{
    /**
     * The commands to be registered.
     *
     * @var array
     */
    protected $commands = [
        'Refresh' => 'orchestra.command.refresh',
    ];

    /**
     * Register the command.
     *
     * @return void
     */
    protected function registerRefreshCommand()
    {
        $this->app->singleton('orchestra.command.refresh', function ($app) {
            return new RefreshCommand($app['orchestra.app'], $app['orchestra.platform.memory']);
        });
    }
}
