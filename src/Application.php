<?php

namespace Orchestra\Foundation;

use Illuminate\Events\EventServiceProvider;
use Orchestra\Routing\RoutingServiceProvider;
use Illuminate\Foundation\Application as BaseApplication;
use Orchestra\Contracts\Foundation\Application as ApplicationContract;

class Application extends BaseApplication implements ApplicationContract
{
    /**
     * The custom vendor path defined by the developer.
     *
     * @var string
     */
    protected $vendorPath;

    /**
     * Register all of the base service providers.
     *
     * @return void
     */
    protected function registerBaseServiceProviders()
    {
        $this->register(new EventServiceProvider($this));

        $this->register(new RoutingServiceProvider($this));
    }

    /**
     * Bind all of the application paths in the container.
     *
     * @return void
     */
    protected function bindPathsInContainer()
    {
        parent::bindPathsInContainer();

        $this->instance('path.vendor', $this->vendorPath());
    }

    /**
     * Get the path to the application configuration files.
     *
     * @return string
     */
    public function configPath()
    {
        return $this->basePath.DIRECTORY_SEPARATOR.'resources'.DIRECTORY_SEPARATOR.'config';
    }

    /**
     * Get the path to the database directory.
     *
     * @return string
     */
    public function databasePath()
    {
        return $this->databasePath ?: $this->basePath.DIRECTORY_SEPARATOR.'resources'.DIRECTORY_SEPARATOR.'database';
    }

    /**
     * Get the path to the vendor directory.
     *
     * @return string
     */
    public function vendorPath()
    {
        return $this->databasePath ?: $this->basePath.DIRECTORY_SEPARATOR.'vendor';
    }

    /**
     * Set the vendor directory.
     *
     * @param  string  $path
     *
     * @return $this
     */
    public function useVendorPath($path)
    {
        $this->vendorPath = $path;

        $this->instance('path.vendor', $path);

        return $this;
    }

    /**
     * Get the path to the cached extension.json file.
     *
     * @return string
     */
    public function getCachedExtensionServicesPath()
    {
        return $this->basePath().'/bootstrap/cache/extension.php';
    }

    /**
     * Flush the container of all bindings and resolved instances.
     *
     * @return void
     */
    public function flush()
    {
        parent::flush();

        $this->hasBeenBootstrapped = false;
    }
}
