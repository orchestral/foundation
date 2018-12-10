<?php

namespace Orchestra\Foundation\Providers;

use Orchestra\Model\Role;
use Orchestra\Foundation\Auth\User;
use Illuminate\Support\ServiceProvider;
use Orchestra\Foundation\Publisher\Filesystem;
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
    protected function registerPublisher(): void
    {
        $this->app->singleton('orchestra.publisher', function (Application $app) {
            $memory = $app->make('orchestra.platform.memory');

            return (new PublisherManager($app))->attach($memory);
        });
    }

    /**
     * Register the service provider for filesystem publisher.
     *
     * @return void
     */
    protected function registerFilesystemPublisher(): void
    {
        $this->app->singleton('orchestra.publisher.filesystem', function (Application $app) {
            return new Filesystem($app);
        });
    }

    /**
     * Register the service provider for user.
     *
     * @return void
     */
    protected function registerRoleEloquent(): void
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
    protected function registerUserEloquent(): void
    {
        $this->app->bind('orchestra.user', function () {
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
     * @return mixed
     */
    protected function getConfig(string $name, $default = null)
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
