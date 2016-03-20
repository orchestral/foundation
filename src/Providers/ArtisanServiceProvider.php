<?php

namespace Orchestra\Foundation\Providers;

use Illuminate\Contracts\Foundation\Application;
use Orchestra\Config\Console\ConfigCacheCommand;
use Orchestra\Foundation\Console\Commands\OptimizeCommand;
use Orchestra\Foundation\Console\Commands\ClearCompiledCommand;
use Illuminate\Foundation\Providers\ArtisanServiceProvider as ServiceProvider;

class ArtisanServiceProvider extends ServiceProvider
{
    /**
     * Register the command.
     *
     * @return void
     */
    protected function registerClearCompiledCommand()
    {
        $this->app->singleton('command.clear-compiled', function () {
            return new ClearCompiledCommand();
        });
    }

    /**
     * Register the command.
     *
     * @return void
     */
    protected function registerConfigCacheCommand()
    {
        $this->app->singleton('command.config.cache', function (Application $app) {
            return new ConfigCacheCommand($app->make('files'));
        });
    }

    /**
     * Register the command.
     *
     * @return void
     */
    protected function registerOptimizeCommand()
    {
        $this->app->singleton('command.optimize', function (Application $app) {
            return new OptimizeCommand($app->make('composer'));
        });
    }
}
