<?php namespace Orchestra\Foundation\Providers;

use Illuminate\Support\ServiceProvider;

class ExtensionServiceProvider extends ServiceProvider {

    /**
     * Available orchestra extensions
     * 
     * @var array
     */
    protected $extensions = [];

    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    {
        $extension = $this->app['orchestra.extension'];

        foreach ($this->extensions as $name => $path) {
            if (is_numeric($name)) {
                $extension->finder()->addPath(base_path().'/'.$path);
            } else {
                $extension->register($name, base_path().'/'.$path);
            }
        }
    }

    /**
     * Register the service provider
     * 
     * @return void
     */
    public function register()
    {
        //
    }

}
