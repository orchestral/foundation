<?php namespace Orchestra\Foundation\Support\Providers;

use Illuminate\Support\ServiceProvider;

class ExtensionServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var boolean
     */
    protected $defer = true;

    /**
     * Available orchestra extensions
     *
     * @var array
     */
    protected $extensions = [];

    /**
     * Register the service provider
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    {
        $finder = $this->app['orchestra.extension.finder'];

        foreach ($this->extensions as $name => $path) {
            if (is_numeric($name)) {
                $finder->addPath($path);
            } else {
                $finder->registerExtension($name, $path);
            }
        }
    }

    /**
     * Get the events that trigger this service provider to register.
     *
     * @return array
     */
    public function when()
    {
        return ['orchestra.extension: detecting'];
    }
}
