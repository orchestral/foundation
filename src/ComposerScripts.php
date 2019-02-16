<?php

namespace Orchestra\Foundation;

use Composer\Script\Event;

class ComposerScripts
{
    /**
     * Handle the post-install Composer event.
     *
     * @param  \Composer\Script\Event  $event
     *
     * @return void
     */
    public static function postInstall(Event $event): void
    {
        require_once $event->getComposer()->getConfig()->get('vendor-dir').'/autoload.php';

        self::clearCompiled();
    }

    /**
     * Handle the post-update Composer event.
     *
     * @param  \Composer\Script\Event  $event
     *
     * @return void
     */
    public static function postUpdate(Event $event): void
    {
        require_once $event->getComposer()->getConfig()->get('vendor-dir').'/autoload.php';

        static::clearCompiled();
    }

    /**
     * Handle the post-autoload-dump Composer event.
     *
     * @param  \Composer\Script\Event  $event
     *
     * @return void
     */
    public static function postAutoloadDump(Event $event): void
    {
        require_once $event->getComposer()->getConfig()->get('vendor-dir').'/autoload.php';

        static::clearCompiled();
    }

    /**
     * Clear the cached Laravel bootstrapping files.
     *
     * @return void
     */
    private static function clearCompiled(): void
    {
        $laravel = new Application(getcwd());

        if (\file_exists($servicesPath = $laravel->getCachedServicesPath())) {
            @\unlink($servicesPath);
        }

        if (\file_exists($packagesPath = $laravel->getCachedPackagesPath())) {
            @\unlink($packagesPath);
        }

        if (\file_exists($extensionServicesPath = $laravel->getCachedExtensionServicesPath())) {
            @\unlink($extensionServicesPath);
        }
    }
}
