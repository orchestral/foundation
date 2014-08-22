<?php namespace Orchestra\Foundation\Providers;

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
        $extension = $this->app['orchestra.extension'];

        foreach ($this->extensions as $name => $path) {
            if (is_numeric($name)) {
                $extension->finder()->addPath($path);
            } else {
                $extension->register($name, $path);
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
