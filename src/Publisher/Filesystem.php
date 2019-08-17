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

        \rescue(function () use ($app, $config, $name) {
            $basePath = "{$config->basePath}{$name}/";

            $this->changePermission(
                $config->workingPath, 0777, $config->recursively
            );

            $this->prepareDirectory($basePath, 0777);
        });

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
