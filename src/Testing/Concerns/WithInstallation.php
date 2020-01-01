<?php

namespace Orchestra\Foundation\Testing\Concerns;

use Illuminate\Foundation\Testing\PendingCommand;
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
        $artisan = function ($command) {
            \tap($this->artisan($command), static function ($console) {
                if ($console instanceof PendingCommand) {
                    $console->run();
                }
            });
        };

        if (! $this->app->bound(InstallationContract::class)) {
            $this->app->register(InstallerServiceProvider::class);
        }

        $installer = $this->app->make(InstallationContract::class);

        $installer->bootInstallerFiles();
        $installer->migrate();

        $this->beforeApplicationDestroyed(static function () use ($artisan) {
            $artisan('migrate:rollback');
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
        $artisan = function ($command) {
            \tap($this->artisan($command), static function ($console) {
                if ($console instanceof PendingCommand) {
                    $console->run();
                }
            });
        };

        if (\is_null($installer)) {
            $installer = $this->makeInstaller();
        }

        $artisan('migrate');

        $this->adminUser = $this->createAdminUser();

        $installer->create($this->adminUser, [
            'site_name' => $config['name'] ?? 'Orchestra Platform',
            'email' => $config['email'] ?? $this->adminUser->email,
        ]);

        $this->app->instance('orchestra.installed', true);

        $this->beforeApplicationDestroyed(function () use ($artisan) {
            $artisan('migrate:rollback');
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
