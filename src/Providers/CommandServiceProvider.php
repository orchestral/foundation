<?php

namespace Orchestra\Foundation\Providers;

use Illuminate\Contracts\Foundation\Application;
use Orchestra\Foundation\Console\Commands\AssembleCommand;
use Orchestra\Foundation\Console\Commands\ConfigureMailCommand;
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
        'ConfigureMail' => 'orchestra.commands.configure-mail',
    ];

    /**
     * Register the command.
     *
     * @return void
     */
    protected function registerAssembleCommand(): void
    {
        $this->app->singleton('orchestra.commands.assemble', static function (Application $app) {
            $foundation = $app->make('orchestra.app');

            return new AssembleCommand($foundation, $foundation->memory());
        });
    }

    /**
     * Register the command.
     *
     * @return void
     */
    protected function registerConfigureMailCommand(): void
    {
        $this->app->singleton('orchestra.commands.configure-mail', static function () {
            return new ConfigureMailCommand();
        });
    }
}
