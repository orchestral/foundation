<?php

namespace Orchestra\Foundation\Providers;

use Illuminate\Contracts\Container\Container;
use Orchestra\Config\Console\ConfigCacheCommand;
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
        $this->app->singleton('command.clear-compiled', static function () {
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
        $this->app->singleton('command.config.cache', static function (Container $app) {
            return new ConfigCacheCommand($app->make('files'));
        });
    }
}
