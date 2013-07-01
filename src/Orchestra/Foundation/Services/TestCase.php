<?php namespace Orchestra\Foundation\Services;

use Orchestra\Testbench\TestCase as TestbenchTestCase;

abstract class TestCase extends TestbenchTestCase {

	/**
	 * Get application aliases.
	 *
	 * @access protected
	 * @return array
	 */
	protected function getApplicationAliases()
	{
		return array();
	}

	/**
	 * Get package aliases.
	 *
	 * @access protected
	 * @return array
	 */
	protected function getPackageAliases()
	{
		return array(
			'Form' => 'Illuminate\Support\Facades\Form',
			'HTML' => 'Illuminate\Support\Facades\HTML',
		);
	}
	/**
	 * Get application providers.
	 *
	 * @access protected
	 * @return array
	 */
	protected function getApplicationProviders()
	{
		return array();
	}

	/**
	 * Get package providers.
	 *
	 * @access protected
	 * @return array
	 */
	protected function getPackageProviders()
	{
		return array(
			'Illuminate\Foundation\Providers\ArtisanServiceProvider',
			'Illuminate\Cache\CacheServiceProvider',
			'Illuminate\Session\CommandsServiceProvider',
			'Illuminate\Foundation\Providers\ComposerServiceProvider',
			'Illuminate\Routing\ControllerServiceProvider',
			'Illuminate\Cookie\CookieServiceProvider',
			'Illuminate\Database\DatabaseServiceProvider',
			'Illuminate\Encryption\EncryptionServiceProvider',
			'Illuminate\Filesystem\FilesystemServiceProvider',
			'Illuminate\Hashing\HashServiceProvider',
			'Illuminate\Log\LogServiceProvider',
			'Illuminate\Mail\MailServiceProvider',
			'Illuminate\Database\MigrationServiceProvider',
			'Illuminate\Pagination\PaginationServiceProvider',
			'Illuminate\Foundation\Providers\PublisherServiceProvider',
			'Illuminate\Queue\QueueServiceProvider',
			'Illuminate\Redis\RedisServiceProvider',
			'Illuminate\Session\SessionServiceProvider',
			'Illuminate\Translation\TranslationServiceProvider',
			'Illuminate\Validation\ValidationServiceProvider',
			'Illuminate\View\ViewServiceProvider',

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
}
