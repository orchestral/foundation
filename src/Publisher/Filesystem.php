<?php

namespace Orchestra\Foundation\Publisher;

use Illuminate\Filesystem\Filesystem as File;
use Illuminate\Contracts\Foundation\Application;
use Orchestra\Contracts\Publisher\Uploader as UploaderContract;

class Filesystem extends Uploader implements UploaderContract
{
    /**
     * Application instance.
     *
     * @var \Illuminate\Filesystem\Filesystem
     */
    protected $files;

    /**
     * Construct a filesystem publisher instance.
     *
     * @param \Illuminate\Contracts\Foundation\Application  $app
     */
    public function __construct(Application $app)
    {
        $this->setContainer($app);
    }

    /**
     * Connect to the service.
     *
     * @param  array  $config
     *
     * @return void
     */
    public function connect(array $config = []): void
    {
        //
    }

    /**
     * Upload the file.
     *
     * @param  string  $name
     *
     * @return bool
     */
    public function upload(string $name): bool
    {
        $app = $this->getContainer();
        $config = $this->destination($name, $recursively);

        try {
            $basePath = "{$config->basePath}{$name}/";

            if (! $config->folderExist) {
                $app['files']->makeDirectory($basePath, 0777, true);
            }

            $this->changePermission(
                $config->workingPath, 0777, $config->recursively
            );
        } catch (RuntimeException $e) {
            // We found an exception with Filesystem, but it would be hard to say
            // extension can't be activated, let's try activating the
            // extension and if it failed, we should actually catching
            // those exception instead.
        }

        $app['orchestra.extension']->activate($name);

        $this->changePermission($config->workingPath, 0755, $config->recursively);

        return true;
    }

    /**
     * Verify that the driver is connected to a service.
     *
     * @return bool
     */
    public function connected(): bool
    {
        return true;
    }
}
