<?php

namespace Orchestra\Foundation\Testing\Concerns;

use Illuminate\Contracts\Console\Kernel as ConsoleKernel;
use Orchestra\Contracts\Installation\Installation as InstallationContract;
use Orchestra\Installation\InstallerServiceProvider;

trait WithInstallation
{
    /**
     * Make Orchestra Platform installer.
     *
     * @return \Orchestra\Contracts\Installation\Installation
     */
    protected function makeInstaller(): InstallationContract
    {
        $artisan = function ($command, array $parameters = []) {
            $this->app[ConsoleKernel::class]->call($command, $parameters);
        };

        if (! $this->app->bound(InstallationContract::class)) {
            $this->app->register(InstallerServiceProvider::class);
        }

        $installer = $this->app->make(InstallationContract::class);

        $installer->bootInstallerFiles();
        $installer->migrate();

        $this->beforeApplicationDestroyed(static function () use ($artisan) {
            $artisan('migrate:rollback', ['--no-interaction' => true]);
        });

        return $installer;
    }

    /**
     * Install Orchestra Platform and get the administrator user.
     *
     * @param  \Orchestra\Contracts\Installation\Installation|null  $installer
     * @param  array  $config
     *
     * @return \Orchestra\Foundation\Auth\User
     */
    protected function runInstallation(?InstallationContract $installer = null, array $config = [])
    {
        $artisan = function ($command, array $parameters = []) {
            $this->app[ConsoleKernel::class]->call($command, $parameters);
        };

        if (\is_null($installer)) {
            $installer = $this->makeInstaller();
        }

        $artisan('migrate', ['--no-interaction' => true]);

        $this->adminUser = $this->createAdminUser();

        $installer->create($this->adminUser, [
            'site_name' => $config['name'] ?? 'Orchestra Platform',
            'email' => $config['email'] ?? $this->adminUser->email,
        ]);

        $this->app->instance('orchestra.installed', true);

        $this->beforeApplicationDestroyed(function () use ($artisan) {
            $artisan('migrate:rollback', ['--no-interaction' => true]);
            $this->app->instance('orchestra.installed', false);
        });

        return $this->adminUser;
    }

    /**
     * Create admin user.
     *
     * @return \Orchestra\Foundation\Auth\User
     */
    abstract protected function createAdminUser();
}
