<?php namespace Orchestra\Foundation\Providers;

use Orchestra\Model\Role;
use Orchestra\Model\User;
use Illuminate\Support\ServiceProvider;
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
        $this->app->singleton('orchestra.publisher', function ($app) {
            $memory = $app['orchestra.platform.memory'];

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
            return new Role();
        });
    }

    /**
     * Register the service provider for user.
     *
     * @return void
     */
    protected function registerUserEloquent()
    {
        $this->app->bind('orchestra.user', function () {
            return new User();
        });
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
