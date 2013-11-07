<?php namespace Orchestra\Foundation;

use Illuminate\Support\ServiceProvider;
use Orchestra\Support\Ftp as FtpClient;
use Orchestra\Model\Role;
use Orchestra\Model\User;

class SiteServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var boolean
     */
    protected $defer = true;

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->registerMailer();
        $this->registerPublisher();
        $this->registerSite();
        $this->registerRoleEloquent();
        $this->registerUserEloquent();
    }

    /**
     * Register the service provider for mail.
     *
     * @return void
     */
    protected function registerMailer()
    {
        $this->app['orchestra.mail'] = $this->app->share(function ($app) {
            return new Mail($app);
        });
    }

    /**
     * Register the service provider for publisher.
     *
     * @return void
     */
    protected function registerPublisher()
    {
        $this->app['orchestra.publisher.ftp'] = $this->app->share(function ($app) {
            return new FtpClient;
        });

        $this->app['orchestra.publisher'] = $this->app->share(function ($app) {
            return new Publisher\PublisherManager($app);
        });
    }

    /**
     * Register the service provider for site.
     *
     * @return void
     */
    protected function registerSite()
    {
        $this->app['orchestra.site'] = $this->app->share(function ($app) {
            return new Site($app);
        });
    }

    /**
     * Register the service provider for user.
     *
     * @return void
     */
    protected function registerRoleEloquent()
    {
        $this->app['orchestra.role'] = $this->app->share(function () {
            return new Role;
        });
    }

    /**
     * Register the service provider for user.
     *
     * @return void
     */
    protected function registerUserEloquent()
    {
        $this->app['orchestra.user'] = $this->app->share(function () {
            return new User;
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return array(
            'orchestra.mail', 'orchestra.publisher', 'orchestra.publisher.ftp',
            'orchestra.site', 'orchestra.role', 'orchestra.user',
        );
    }
}
