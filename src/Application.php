<?php

namespace Orchestra\Foundation;

use Illuminate\Events\EventServiceProvider;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Foundation\Application as BaseApplication;
use Illuminate\Foundation\PackageManifest;
use Illuminate\Log\LogServiceProvider;
use Illuminate\Support\Collection;
use Laravie\Dhosa\HotSwap;
use Orchestra\Contracts\Foundation\Application as ApplicationContract;
use Orchestra\Routing\RoutingServiceProvider;

class Application extends BaseApplication implements ApplicationContract
{
    /**
     * The custom vendor path defined by the developer.
     *
     * @var string
     */
    protected $vendorPath;

    /**
     * Register the basic bindings into the container.
     *
     * @return void
     */
    protected function registerBaseBindings()
    {
        parent::registerBaseBindings();

        HotSwap::override('User', Auth\User::class);
    }

    /**
     * Register all of the base service providers.
     *
     * @return void
     */
    protected function registerBaseServiceProviders()
    {
        $this->register(new EventServiceProvider($this));

        $this->register(new RoutingServiceProvider($this));

        $this->register(new LogServiceProvider($this));
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
     * Mark the given provider as registered.
     *
     * @param  \Illuminate\Support\ServiceProvider  $provider
     *
     * @return void
     */
    protected function markAsRegistered($provider)
    {
        $this['events']->dispatch(\get_class($provider), [$provider]);

        parent::markAsRegistered($provider);
    }

    /**
     * Get the path to the application configuration files.
     *
     * @param  string  $path Optionally, a path to append to the config path
     *
     * @return string
     */
    public function configPath($path = '')
    {
        return $this->basePath('config'.($path ? DIRECTORY_SEPARATOR.$path : ''));
    }

    /**
     * Get the path to the database directory.
     *
     * @return string
     */
    public function databasePath($path = '')
    {
        return ($this->databasePath ?: $this->basePath('database')).($path ? DIRECTORY_SEPARATOR.$path : '');
    }

    /**
     * Get the path to the application resource files.
     *
     * @param  string  $path Optionally, a path to append to the config path
     *
     * @return string
     */
    public function resourcesPath($path = '')
    {
        return $this->basePath.DIRECTORY_SEPARATOR.'resources'.($path ? DIRECTORY_SEPARATOR.$path : '');
    }

    /**
     * Get the path to the vendor directory.
     *
     * @return string
     */
    public function vendorPath()
    {
        return $this->vendorPath ?: $this->basePath.DIRECTORY_SEPARATOR.'vendor';
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
     * Register all of the configured providers.
     *
     * @return void
     */
    public function registerConfiguredProviders()
    {
        $providers = Collection::make($this->config['app.providers'])
                        ->partition(static function ($provider) {
                            return \strpos($provider, 'Illuminate\\') === 0
                                || \strpos($provider, 'Orchestra\\') === 0;
                        });

        $providers->splice(1, 0, [$this->make(PackageManifest::class)->providers()]);

        (new ProviderRepository($this, new Filesystem(), $this->getCachedServicesPath()))
                    ->load($providers->collapse()->toArray());
    }

    /**
     * Get the path to the cached extension.json file.
     *
     * @return string
     */
    public function getCachedExtensionServicesPath()
    {
        return $this->bootstrapPath('cache'.DIRECTORY_SEPARATOR.'extension.php');
    }

    /**
     * Flush the container of all bindings and resolved instances.
     *
     * @return void
     */
    public function flush()
    {
        parent::flush();

        HotSwap::flush();

        $this->booted = false;
        $this->hasBeenBootstrapped = false;

        $this->bootingCallbacks = [];
        $this->bootedCallbacks = [];
        $this->reboundCallbacks = [];
        $this->resolvingCallbacks = [];
        $this->terminatingCallbacks = [];
        $this->afterResolvingCallbacks = [];
        $this->globalResolvingCallbacks = [];

        $this->serviceProviders = [];
        $this->deferredServices = [];
        $this->buildStack = [];
    }
}
