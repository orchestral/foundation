<?php

namespace Orchestra\Foundation\Providers;

use Illuminate\Contracts\Foundation\Application;
use Orchestra\Foundation\Console\Commands\AssembleCommand;
use Orchestra\Support\Providers\CommandServiceProvider as ServiceProvider;

class CommandServiceProvider extends ServiceProvider
{
    /**
     * The commands to be registered.
     *
     * @var array
     */
    protected $commands = [
        'Assemble' => 'orchestra.commands.assemble',
    ];

    /**
     * Register the command.
     *
     * @return void
     */
    protected function registerAssembleCommand()
    {
        $this->app->singleton('orchestra.commands.assemble', function (Application $app) {
            $foundation = $app->make('orchestra.app');

            return new AssembleCommand($foundation, $foundation->memory());
        });
    }
}
