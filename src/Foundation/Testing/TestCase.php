<?php namespace Orchestra\Foundation\Testing;

use Orchestra\Kernel\Application;
use Orchestra\Testbench\TestCase as TestbenchTestCase;

abstract class TestCase extends TestbenchTestCase
{
    /**
     * Get application aliases.
     *
     * @return array
     */
    protected function getApplicationAliases()
    {
        return [
            'App'        => 'Illuminate\Support\Facades\App',
            'ACL'        => 'Orchestra\Support\Facades\ACL',
            'Artisan'    => 'Illuminate\Support\Facades\Artisan',
            'Asset'      => 'Orchestra\Support\Facades\Asset',
            'Auth'       => 'Illuminate\Support\Facades\Auth',
            'Blade'      => 'Illuminate\Support\Facades\Blade',
            'Cache'      => 'Illuminate\Support\Facades\Cache',
            'Config'     => 'Illuminate\Support\Facades\Config',
            'Cookie'     => 'Illuminate\Support\Facades\Cookie',
            'Crypt'      => 'Illuminate\Support\Facades\Crypt',
            'DB'         => 'Illuminate\Support\Facades\DB',
            'Event'      => 'Illuminate\Support\Facades\Event',
            'Facile'     => 'Orchestra\Support\Facades\Facile',
            'File'       => 'Illuminate\Support\Facades\File',
            'Form'       => 'Orchestra\Support\Facades\Form',
            'Hash'       => 'Illuminate\Support\Facades\Hash',
            'HTML'       => 'Orchestra\Support\Facades\HTML',
            'Input'      => 'Illuminate\Support\Facades\Input',
            'Lang'       => 'Illuminate\Support\Facades\Lang',
            'Log'        => 'Illuminate\Support\Facades\Log',
            'Mail'       => 'Illuminate\Support\Facades\Mail',
            'Paginator'  => 'Illuminate\Support\Facades\Paginator',
            'Password'   => 'Illuminate\Support\Facades\Password',
            'Queue'      => 'Illuminate\Support\Facades\Queue',
            'Redirect'   => 'Illuminate\Support\Facades\Redirect',
            'Redis'      => 'Illuminate\Support\Facades\Redis',
            'Request'    => 'Illuminate\Support\Facades\Request',
            'Response'   => 'Illuminate\Support\Facades\Response',
            'Route'      => 'Illuminate\Support\Facades\Route',
            'Schema'     => 'Illuminate\Support\Facades\Schema',
            'Session'    => 'Illuminate\Support\Facades\Session',
            'Theme'      => 'Orchestra\Support\Facades\Theme',
            'URL'        => 'Illuminate\Support\Facades\URL',
            'Validator'  => 'Illuminate\Support\Facades\Validator',
            'View'       => 'Illuminate\Support\Facades\View',
        ];
    }

    /**
     * Get package aliases.
     *
     * @return array
     */
    protected function getPackageAliases()
    {
        return [];
    }

    /**
     * Get application middlewares.
     *
     * @return array
     */
    protected function getApplicationMiddlewares()
    {
        return [
            'Illuminate\Cookie\Middleware\Guard',
            'Illuminate\Cookie\Middleware\Queue',
            'Illuminate\Session\Middleware\Reader',
            'Illuminate\Session\Middleware\Writer',
            'Illuminate\View\Middleware\ErrorBinder',
        ];
    }

    /**
     * Get application providers.
     *
     * @return array
     */
    protected function getApplicationProviders()
    {
        return [
            /*
             * Laravel Framework Service Providers...
             */
            'Illuminate\Foundation\Providers\ArtisanServiceProvider',
            'Illuminate\Cache\CacheServiceProvider',
            'Illuminate\Foundation\Providers\ConsoleSupportServiceProvider',
            'Illuminate\Cookie\CookieServiceProvider',
            'Illuminate\Database\DatabaseServiceProvider',
            'Illuminate\Encryption\EncryptionServiceProvider',
            'Illuminate\Filesystem\FilesystemServiceProvider',
            'Illuminate\Foundation\Providers\FoundationServiceProvider',
            'Illuminate\Hashing\HashServiceProvider',
            'Illuminate\Log\LogServiceProvider',
            'Illuminate\Mail\MailServiceProvider',
            'Illuminate\Pagination\PaginationServiceProvider',
            'Illuminate\Foundation\Providers\PublisherServiceProvider',
            'Illuminate\Queue\QueueServiceProvider',
            'Illuminate\Redis\RedisServiceProvider',
            'Illuminate\Session\SessionServiceProvider',
            'Illuminate\Validation\ValidationServiceProvider',
            'Illuminate\View\ViewServiceProvider',

            /*
             * Orchestra Platform Service Providers...
             */
            'Orchestra\Asset\AssetServiceProvider',
            'Orchestra\Auth\AuthServiceProvider',
            'Orchestra\Routing\ControllerServiceProvider',
            'Orchestra\Debug\DebugServiceProvider',
            'Orchestra\View\DecoratorServiceProvider',
            'Orchestra\Extension\ExtensionServiceProvider',
            'Orchestra\Facile\FacileServiceProvider',
            'Orchestra\Html\HtmlServiceProvider',
            'Orchestra\Memory\MemoryServiceProvider',
            'Orchestra\Messages\MessagesServiceProvider',
            'Orchestra\Notifier\NotifierServiceProvider',
            'Orchestra\Optimize\OptimizeServiceProvider',
            'Orchestra\Auth\Passwords\PasswordResetServiceProvider',
            'Orchestra\Publisher\PublisherServiceProvider',
            'Orchestra\Resources\ResourcesServiceProvider',
            'Orchestra\Foundation\SupportServiceProvider',
            'Orchestra\Translation\TranslationServiceProvider',
            'Orchestra\View\ViewServiceProvider',
            'Orchestra\Widget\WidgetServiceProvider',

            'Orchestra\Foundation\ConsoleSupportServiceProvider',
            'Orchestra\Foundation\FilterServiceProvider',
            'Orchestra\Foundation\FoundationServiceProvider',
        ];
    }

    /**
     * Get package providers.
     *
     * @return array
     */
    protected function getPackageProviders()
    {
        return [];
    }

     /**
     * Define environment setup.
     *
     * @param  \Illuminate\Foundation\Application   $app
     * @return void
     */
    protected function getEnvironmentSetUp($app)
    {
        $app['router']->disableFilters();
    }

    /**
     * Resolve application implementation.
     *
     * @return \Illuminate\Foundation\Application
     */
    protected function resolveApplication()
    {
        return new Application($this->getBasePath());
    }
}
