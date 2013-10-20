<?php namespace Orchestra\Foundation\Services;

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
        return array(
            'App'             => 'Illuminate\Support\Facades\App',
            'Artisan'         => 'Illuminate\Support\Facades\Artisan',
            'Asset'           => 'Orchestra\Support\Facades\Asset',
            'Auth'            => 'Illuminate\Support\Facades\Auth',
            'Blade'           => 'Illuminate\Support\Facades\Blade',
            'Cache'           => 'Illuminate\Support\Facades\Cache',
            'ClassLoader'     => 'Illuminate\Support\ClassLoader',
            'Config'          => 'Illuminate\Support\Facades\Config',
            'Controller'      => 'Illuminate\Routing\Controllers\Controller',
            'Cookie'          => 'Illuminate\Support\Facades\Cookie',
            'Crypt'           => 'Illuminate\Support\Facades\Crypt',
            'DB'              => 'Illuminate\Support\Facades\DB',
            'Eloquent'        => 'Illuminate\Database\Eloquent\Model',
            'Event'           => 'Illuminate\Support\Facades\Event',
            'Facile'          => 'Orchestra\Support\Facades\Facile',
            'File'            => 'Illuminate\Support\Facades\File',
            'Form'            => 'Illuminate\Support\Facades\Form',
            'Hash'            => 'Illuminate\Support\Facades\Hash',
            'HTML'            => 'Illuminate\Support\Facades\HTML',
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
            'SSH'             => 'Illuminate\Support\Facades\SSH',
            'URL'             => 'Illuminate\Support\Facades\URL',
            'Validator'       => 'Illuminate\Support\Facades\Validator',
            'View'            => 'Illuminate\Support\Facades\View',
        );
    }

    /**
     * Get package aliases.
     *
     * @return array
     */
    protected function getPackageAliases()
    {
        return array();
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
            'Illuminate\Foundation\Providers\CommandCreatorServiceProvider',
            'Illuminate\Session\CommandsServiceProvider',
            'Illuminate\Foundation\Providers\ComposerServiceProvider',
            'Illuminate\Routing\ControllerServiceProvider',
            'Illuminate\Cookie\CookieServiceProvider',
            'Illuminate\Database\DatabaseServiceProvider',
            'Illuminate\Encryption\EncryptionServiceProvider',
            'Illuminate\Filesystem\FilesystemServiceProvider',
            'Illuminate\Hashing\HashServiceProvider',
            'Illuminate\Foundation\Providers\KeyGeneratorServiceProvider',
            'Illuminate\Log\LogServiceProvider',
            'Illuminate\Mail\MailServiceProvider',
            'Illuminate\Foundation\Providers\MaintenanceServiceProvider',
            'Illuminate\Database\MigrationServiceProvider',
            'Illuminate\Foundation\Providers\OptimizeServiceProvider',
            'Illuminate\Pagination\PaginationServiceProvider',
            'Illuminate\Foundation\Providers\PublisherServiceProvider',
            'Illuminate\Queue\QueueServiceProvider',
            'Illuminate\Redis\RedisServiceProvider',
            'Illuminate\Foundation\Providers\RouteListServiceProvider',
            'Illuminate\Database\SeedServiceProvider',
            'Illuminate\Foundation\Providers\ServerServiceProvider',
            'Illuminate\Session\SessionServiceProvider',
            'Illuminate\Foundation\Providers\TinkerServiceProvider',
            'Illuminate\Translation\TranslationServiceProvider',
            'Illuminate\Validation\ValidationServiceProvider',
            'Illuminate\View\ViewServiceProvider',
            'Illuminate\Workbench\WorkbenchServiceProvider',

            'Orchestra\Asset\AssetServiceProvider',
            'Orchestra\Auth\AuthServiceProvider',
            'Orchestra\View\DecoratorServiceProvider',
            'Orchestra\Extension\ExtensionServiceProvider',
            'Orchestra\Facile\FacileServiceProvider',
            'Orchestra\Html\HtmlServiceProvider',
            'Orchestra\Memory\MemoryServiceProvider',
            'Orchestra\Support\MessagesServiceProvider',
            'Orchestra\Extension\PublisherServiceProvider',
            'Orchestra\Foundation\Reminders\ReminderServiceProvider',
            'Orchestra\Resources\ResourcesServiceProvider',
            'Orchestra\Foundation\SiteServiceProvider',
            'Orchestra\View\ViewServiceProvider',
            'Orchestra\Widget\WidgetServiceProvider',

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
