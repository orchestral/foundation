<?php

namespace Orchestra\Foundation\Composer;

use Orchestra\Foundation\Application;
use Illuminate\Foundation\ComposerScripts;

class Command extends ComposerScripts
{
    /**
     * Clear the cached Laravel bootstrapping files.
     *
     * @return void
     */
    private static function clearCompiled()
    {
        $laravel = new Application(getcwd());

        if (file_exists($compiledPath = $laravel->getCachedCompilePath())) {
            @unlink($compiledPath);
        }

        if (file_exists($servicesPath = $laravel->getCachedServicesPath())) {
            @unlink($servicesPath);
        }

        if (file_exists($extensionServicesPath = $laravel->getCachedExtensionServicesPath())) {
            @unlink($extensionServicesPath);
        }
    }
}
