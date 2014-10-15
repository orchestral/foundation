<?php namespace Orchestra\Foundation\Testing;

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
            'App'             => 'Illuminate\Support\Facades\App',
            'Artisan'         => 'Illuminate\Support\Facades\Artisan',
            'Asset'           => 'Orchestra\Support\Facades\Asset',
            'Auth'            => 'Illuminate\Support\Facades\Auth',
            'Blade'           => 'Illuminate\Support\Facades\Blade',
            'Cache'           => 'Illuminate\Support\Facades\Cache',
            'Config'          => 'Illuminate\Support\Facades\Config',
            'Cookie'          => 'Illuminate\Support\Facades\Cookie',
            'Crypt'           => 'Illuminate\Support\Facades\Crypt',
            'DB'              => 'Illuminate\Support\Facades\DB',
            'Eloquent'        => 'Illuminate\Database\Eloquent\Model',
            'Event'           => 'Illuminate\Support\Facades\Event',
            'Facile'          => 'Orchestra\Support\Facades\Facile',
            'File'            => 'Illuminate\Support\Facades\File',
            'Form'            => 'Orchestra\Support\Facades\Form',
            'Hash'            => 'Illuminate\Support\Facades\Hash',
            'HTML'            => 'Orchestra\Support\Facades\HTML',
            'Input'           => 'Illuminate\Support\Facades\Input',
            'Lang'            => 'Illuminate\Support\Facades\Lang',
            'Log'             => 'Illuminate\Support\Facades\Log',
            'Mail'            => 'Illuminate\Support\Facades\Mail',
            'Paginator'       => 'Illuminate\Support\Facades\Paginator',
            'Password'        => 'Illuminate\Support\Facades\Password',
            'Queue'           => 'Illuminate\Support\Facades\Queue',
            'Redirect'        => 'Illuminate\Support\Facades\Redirect',
            'Redis'           => 'Illuminate\Support\Facades\Redis',
            'Request'         => 'Illuminate\Support\Facades\Request',
            'Response'        => 'Illuminate\Support\Facades\Response',
            'Route'           => 'Illuminate\Support\Facades\Route',
            'Schema'          => 'Illuminate\Support\Facades\Schema',
            'Seeder'          => 'Illuminate\Database\Seeder',
            'Session'         => 'Illuminate\Support\Facades\Session',
            'Str'             => 'Orchestra\Support\Str',
            'Theme'           => 'Orchestra\Support\Facades\Theme',
            'URL'             => 'Illuminate\Support\Facades\URL',
            'Validator'       => 'Illuminate\Support\Facades\Validator',
            'View'            => 'Illuminate\Support\Facades\View',
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
     * Get application providers.
     *
     * @return array
     */
    protected function getApplicationProviders()
    {
        return array(
            'Illuminate\Foundation\Providers\ArtisanServiceProvider',
            'Illuminate\Cache\CacheServiceProvider',
            'Illuminate\Foundation\Providers\ConsoleSupportServiceProvider',
            'Illuminate\Cookie\CookieServiceProvider',
            'Illuminate\Database\DatabaseServiceProvider',
            'Illuminate\Encryption\EncryptionServiceProvider',
            'Illuminate\Filesystem\FilesystemServiceProvider',
            'Illuminate\Foundation\Providers\FormRequestServiceProvider',
            'Illuminate\Hashing\HashServiceProvider',
            'Illuminate\Log\LogServiceProvider',
            'Illuminate\Mail\MailServiceProvider',
            'Illuminate\Database\MigrationServiceProvider',
            'Illuminate\Pagination\PaginationServiceProvider',
            'Illuminate\Foundation\Providers\PublisherServiceProvider',
            'Illuminate\Queue\QueueServiceProvider',
            'Illuminate\Redis\RedisServiceProvider',
            'Illuminate\Database\SeedServiceProvider',
            'Illuminate\Session\SessionServiceProvider',
            'Illuminate\Translation\TranslationServiceProvider',
            'Illuminate\Validation\ValidationServiceProvider',
            'Illuminate\View\ViewServiceProvider',

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
            'Orchestra\Foundation\SiteServiceProvider',
            'Orchestra\View\ViewServiceProvider',
            'Orchestra\Widget\WidgetServiceProvider',

            //'Orchestra\Foundation\FilterServiceProvider',
            'Orchestra\Foundation\ConsoleSupportServiceProvider',
            'Orchestra\Foundation\FoundationServiceProvider',
        );
    }

    /**
     * Get package providers.
     *
     * @return array
     */
    protected function getPackageProviders()
    {
        return array();
    }
}
