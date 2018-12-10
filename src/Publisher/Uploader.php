<?php

namespace Orchestra\Foundation\Publisher;

use Illuminate\Support\Str;
use Illuminate\Support\Fluent;
use Illuminate\Contracts\Container\Container;
use Illuminate\Contracts\Foundation\Application;

abstract class Uploader
{
    /**
     * Change CHMOD permission.
     *
     * @param  string  $path
     * @param  int  $mode
     * @param  bool  $recursively
     * @param  \Closure  $callback
     *
     * @return void
     */
    protected function changePermission(string $path, $mode = 0755, bool $recursively, Closure $callback = null)
    {
        if ($recursively) {
            $this->chmodRecursively($path, $mode);
        } else {
            $this->chmod($path, $mode);

            if (is_callable($callback)) {
                $callback();
            }
        }
    }

    /**
     * CHMOD a directory/file.
     *
     * @param  string  $path
     * @param  int  $mode
     *
     * @return mixed
     */
    protected function chmod(string $path, $mode = 0755): void
    {
        $this->getContainer()['files']->chmod($path, $mode);
    }

    /**
     * CHMOD both file and directory recursively.
     *
     * @param  string  $path
     * @param  int. $mode
     *
     * @return bool
     */
    protected function chmodRecursively(string $path, $mode = 0755): bool
    {
        $filesystem = $this->getContainer()['files'];

        $filesystem->chmod($path, $mode);

        $lists = $filesystem->allFiles($path);

        $ignoredPath = function ($dir) {
            return (substr($dir, -3) === '/..' || substr($dir, -2) === '/.');
        };

        // this is to check if return value is just a single file,
        // avoiding infinite loop when we reach a file.
        if ($lists !== [$path]) {
            foreach ($lists as $dir) {
                // Not a file or folder, ignore it.
                if (! $ignoredPath($dir)) {
                    $this->chmod($dir, $mode, true);
                }
            }
        }

        return true;
    }

    /**
     * Get base path for FTP.
     *
     * @param  string  $path
     *
     * @return string
     */
    protected function basePath(string $path): string
    {
        // This set of preg_match would filter ftp' user is not accessing
        // exact path as path('public'), in most shared hosting ftp' user
        // would only gain access to it's /home/username directory.
        if (preg_match('/^\/(home)\/([a-zA-Z0-9]+)\/(.*)$/', $path, $matches)) {
            $path = '/'.ltrim($matches[3], '/');
        }

        return $path;
    }

    /**
     * Check upload path.
     *
     * @param  string  $name
     * @param  bool    $recursively
     *
     * @return \Illuminate\Support\Fluent
     */
    protected function destination(string $name, bool $recursively = false): Fluent
    {
        $filesystem = $this->getContainer()['files'];
        $folderExist = true;

        $publicPath = $this->basePath($this->getContainer()['path.public']);

        // Start chmod from public/packages directory, if the extension folder
        // is yet to be created, it would be created and own by the web server
        // (Apache or Nginx). If otherwise, we would then emulate chmod -Rf
        $publicPath = rtrim($publicPath, '/').'/';
        $workingPath = $basePath = "{$publicPath}packages/";

        // If the extension directory exist, we should start chmod from the
        // folder instead.
        if ($filesystem->isDirectory($folder = "{$basePath}{$name}/")) {
            $recursively = true;
            $workingPath = $folder;
        } else {
            $folderExist = false;
        }

        // Alternatively if vendor has been created before, we need to
        // change the permission on the vendor folder instead of
        // public/packages.
        if (! $recursively && Str::contains($name, '/')) {
            [$vendor, ] = explode('/', $name);

            if ($filesystem->isDirectory($folder = "{$basePath}{$vendor}/")) {
                $workingPath = $folder;
            }
        }

        return new Fluent([
            'workingPath' => $workingPath,
            'basePath' => $basePath,
            'resursively' => $recursively,
            'folderExist' => $folderExist,
        ]);
    }

    /**
     * Get the Container instance.
     *
     * @return \Illuminate\Contracts\Foundation\Application
     */
    public function getContainer(): Application
    {
        return $this->app;
    }

    /**
     * Set the Application instance.
     *
     * @param  \Illuminate\Contracts\Foundation\Application  $app
     *
     * @return $this
     */
    public function setContainer(Application $app)
    {
        $this->app = $app;

        return $this;
    }
}
