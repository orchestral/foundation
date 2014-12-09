<?php namespace Orchestra\Foundation\Testing;

use Orchestra\Foundation\Application;
use Orchestra\Testbench\TestCase as TestbenchTestCase;

abstract class TestCase extends TestbenchTestCase
{
    /**
     * Get application aliases.
     *
     * @param  \Illuminate\Foundation\Application  $app
     * @return array
     */
    protected function getApplicationAliases($app)
    {
        return [
            'App'        => 'Illuminate\Support\Facades\App',
            'ACL'        => 'Orchestra\Support\Facades\ACL',
            'Artisan'    => 'Illuminate\Support\Facades\Artisan',
            'Asset'      => 'Orchestra\Support\Facades\Asset',
            'Auth'       => 'Illuminate\Support\Facades\Auth',
            'Avatar'     => 'Orchestra\Support\Facades\Avatar',
            'Blade'      => 'Illuminate\Support\Facades\Blade',
            'Cache'      => 'Illuminate\Support\Facades\Cache',
            'Config'     => 'Illuminate\Support\Facades\Config',
            'Cookie'     => 'Illuminate\Support\Facades\Cookie',
            'Crypt'      => 'Illuminate\Support\Facades\Crypt',
            'DB'         => 'Illuminate\Support\Facades\DB',
            'Event'      => 'Illuminate\Support\Facades\Event',
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
     * @param  \Illuminate\Foundation\Application  $app
     * @return array
     */
    protected function getPackageAliases($app)
    {
        return [];
    }

    /**
     * Get application providers.
     *
     * @param  \Illuminate\Foundation\Application  $app
     * @return array
     */
    protected function getApplicationProviders($app)
    {
        return [
            /*
             * Laravel Framework Service Providers...
             */
            'Illuminate\Foundation\Providers\ArtisanServiceProvider',
            'Illuminate\Cache\CacheServiceProvider',
            'Illuminate\Cookie\CookieServiceProvider',
            'Illuminate\Database\DatabaseServiceProvider',
            'Illuminate\Encryption\EncryptionServiceProvider',
            'Illuminate\Filesystem\FilesystemServiceProvider',
            'Illuminate\Foundation\Providers\FoundationServiceProvider',
            'Illuminate\Hashing\HashServiceProvider',
            'Illuminate\Mail\MailServiceProvider',
            'Illuminate\Pagination\PaginationServiceProvider',
            'Illuminate\Queue\QueueServiceProvider',
            'Illuminate\Redis\RedisServiceProvider',
            'Illuminate\Session\SessionServiceProvider',
            'Illuminate\Validation\ValidationServiceProvider',

            /*
             * Orchestra Platform Service Providers...
             */
            'Orchestra\Asset\AssetServiceProvider',
            'Orchestra\Auth\AuthServiceProvider',
            'Orchestra\Authorization\AuthorizationServiceProvider',
            'Orchestra\Avatar\AvatarServiceProvider',
            'Orchestra\Routing\ControllerServiceProvider',
            'Orchestra\View\DecoratorServiceProvider',
            'Orchestra\Extension\ExtensionServiceProvider',
            'Orchestra\Html\HtmlServiceProvider',
            'Orchestra\Memory\MemoryServiceProvider',
            'Orchestra\Messages\MessagesServiceProvider',
            'Orchestra\Notifier\NotifierServiceProvider',
            'Orchestra\Optimize\OptimizeServiceProvider',
            'Orchestra\Auth\Passwords\PasswordResetServiceProvider',
            'Orchestra\Publisher\PublisherServiceProvider',
            'Orchestra\Foundation\Providers\SupportServiceProvider',
            'Orchestra\Translation\TranslationServiceProvider',
            'Orchestra\View\ViewServiceProvider',
            'Orchestra\Widget\WidgetServiceProvider',

            'Orchestra\Foundation\Providers\ConsoleSupportServiceProvider',
            'Orchestra\Foundation\Providers\FilterServiceProvider',
            'Orchestra\Foundation\Providers\FoundationServiceProvider',
        ];
    }

    /**
     * Get package providers.
     *
     * @param  \Illuminate\Foundation\Application  $app
     * @return array
     */
    protected function getPackageProviders($app)
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
        $app = new Application($this->getBasePath());

        $app->bind('Illuminate\Foundation\Bootstrap\LoadConfiguration', 'Orchestra\Config\Bootstrap\LoadConfiguration');

        return $app;
    }

    /**
     * Resolve application implementation.
     *
     * @param \Illuminate\Foundation\Application  $app
     */
    protected function resolveApplicationHttpKernel($app)
    {
        $app->singleton('Illuminate\Contracts\Http\Kernel', 'Orchestra\Foundation\Testing\Http\Kernel');
    }
}
