<?php

namespace Orchestra\Foundation\Providers;

use Orchestra\Model\Role;
use Orchestra\Foundation\Auth\User;
use Illuminate\Support\ServiceProvider;
use Illuminate\Contracts\Foundation\Application;
use Orchestra\Foundation\Publisher\PublisherManager;

class SupportServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = true;

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->registerPublisher();

        $this->registerRoleEloquent();

        $this->registerUserEloquent();
    }

    /**
     * Register the service provider for publisher.
     *
     * @return void
     */
    protected function registerPublisher()
    {
        $this->app->singleton('orchestra.publisher', function (Application $app) {
            $memory = $app->make('orchestra.platform.memory');

            return (new PublisherManager($app))->attach($memory);
        });
    }

    /**
     * Register the service provider for user.
     *
     * @return void
     */
    protected function registerRoleEloquent()
    {
        $this->app->bind('orchestra.role', function () {
            $model = $this->getConfig('models.role', Role::class);

            return new $model();
        });
    }

    /**
     * Register the service provider for user.
     *
     * @return void
     */
    protected function registerUserEloquent()
    {
        $this->app->bind('orchestra.user', function (Application $app) {
            $model = $this->getConfig('models.user', User::class);

            return new $model();
        });
    }

    /**
     * Get configuration value.
     *
     * @param  string  $name
     * @param  mixed  $default
     *
     * @return string
     */
    protected function getConfig($name, $default = null)
    {
        return $this->app->make('config')->get("orchestra/foundation::{$name}", $default);
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [
            'orchestra.publisher', 'orchestra.role', 'orchestra.user',
        ];
    }
}
