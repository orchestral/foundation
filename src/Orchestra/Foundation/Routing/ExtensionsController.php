<?php namespace Orchestra\Foundation\Routing;

use Closure;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Fluent;
use Orchestra\Support\Facades\App;
use Orchestra\Support\Facades\Extension;
use Orchestra\Support\Facades\Messages;
use Orchestra\Support\Facades\Publisher;
use Orchestra\Support\Facades\Site;
use Orchestra\Extension\FilePermissionException;
use Orchestra\Foundation\Services\Html\ExtensionPresenter;

class ExtensionsController extends AdminController {

	/**
	 * Construct Extensions Controller, only authenticated user should be
	 * able to access this controller.
	 *
	 * @return void
	 */
	public function __construct()
	{
		parent::__construct();

		$this->beforeFilter('orchestra.auth');
		$this->beforeFilter('orchestra.manage');
	}

	/**
	 * List all available extensions.
	 * 
	 * GET (:orchestra)/extensions
	 *
	 * @return Response
	 */
	public function getIndex()
	{
		$extensions = Extension::detect();

		Site::set('title', trans("orchestra/foundation::title.extensions.list"));

		return View::make('orchestra/foundation::extensions.index', compact('extensions'));
	}

	/**
	 * Activate an extension.
	 *
	 * GET (:orchestra)/extensions/activate/(:name)
	 *
	 * @param  string   $name   name of the extension
	 * @return Response
	 */
	public function getActivate($name)
	{
		$name = str_replace('.', '/', $name);

		if (Extension::started($name)) return App::abort(404);

		return $this->run($name, function ($name)
		{
			Extension::activate($name);
			Messages::add('success', trans('orchestra/foundation::response.extensions.activate', compact('name')));
		});
	}

	/**
	 * Deactivate an extension.
	 *
	 * GET (:orchestra)/extensions/deactivate/(:name)
	 *
	 * @param  string   $name   name of the extension
	 * @return Response
	 */
	public function getDeactivate($name)
	{
		$name = str_replace('.', '/', $name);

		if ( ! Extension::started($name) and ! Extension::active($name)) return App::abort(404);
		
		Extension::deactivate($name);
		Messages::add('success', trans('orchestra/foundation::response.extensions.deactivate', compact('name')));

		return Redirect::to(handles('orchestra/foundation::extensions'));
	}

	/**
	 * Configure an extension.
	 *
	 * GET (:orchestra)/extensions/configure/(:name)
	 *
	 * @param  string   $name name of the extension
	 * @return Response
	 */
	public function getConfigure($name)
	{
		$name = str_replace('.', '/', $name);

		if ( ! Extension::started($name)) return App::abort(404);

		// Load configuration from memory.
		$memory        = App::memory();
		$activeConfig  = (array) $memory->get("extensions.active.{$name}.config", array());
		$baseConfig    = (array) $memory->get("extension_{$name}", array());
		$eloquent      = new Fluent(array_merge($activeConfig, $baseConfig));
		$extensionName = $memory->get("extensions.available.{$name}.name", $name);

		// Add basic form, allow extension to add custom configuration field
		// to this form using events.
		$form = ExtensionPresenter::form($eloquent, $name);

		Event::fire("orchestra.form: extension.{$name}", array($eloquent, $form));
		Site::set('title', $extensionName);
		Site::set('description', trans("orchestra/foundation::title.extensions.configure"));

		return View::make('orchestra/foundation::extensions.configure', compact('eloquent', 'form'));
	}

	/**
	 * Update extension configuration.
	 *
	 * POST (:orchestra)/extensions/configure/(:name)
	 *
	 * @param  string   $name   name of the extension
	 * @return Response
	 */
	public function postConfigure($name)
	{
		$uid  = $name;
		$name = str_replace('.', '/', $name);

		if ( ! Extension::started($name)) return App::abort(404);

		$input      = Input::all();
		$validation = App::make('Orchestra\Foundation\Services\Validation\Extension')
			->with($input, array("orchestra.validate: extension.{$name}"));

		if ($validation->fails())
		{
			return Redirect::to(handles("orchestra/foundation::extensions/configure/{$uid}"))
					->withInput()
					->withErrors($validation);
		}

		$memory = App::memory();
		$config = (array) $memory->get("extension.active.{$name}.config", array());
		$input  = new Fluent(array_merge($config, $input));

		unset($input['_token']);

		Event::fire("orchestra.saving: extension.{$name}", array( & $input));

		$memory->put("extensions.active.{$name}.config", $input->getAttributes());
		$memory->put("extension_{$name}", $input->getAttributes());
		
		Event::fire("orchestra.saved: extension.{$name}", array($input));

		Messages::add('success', trans("orchestra/foundation::response.extensions.configure", compact('name')));

		return Redirect::to(handles('orchestra/foundation::extensions'));
	}

	/**
	 * Update an extension, run migration and bundle publish command.
	 *
	 * GET (:orchestra)/extensions/update/(:name)
	 *
	 * @param  string   $name   name of the extension
	 * @return Response
	 */
	public function getUpdate($name)
	{
		$name = str_replace('.', '/', $name);

		if ( ! Extension::started($name)) return App::abort(404);

		return $this->run($name, function ($name)
		{
			Extension::publish($name);
			Messages::add('success', trans('orchestra/foundation::response.extensions.update', compact('name')));
		});
	}

	/**
	 * Run installation or update for an extension.
	 * 
	 * @param  string   $name       name of the extension
	 * @param  Closure  $callback
	 * @return Response
	 */
	protected function run($name, Closure $callback)
	{
		try
		{
			// Check if folder is writable via the web instance, this would 
			// avoid issue running Orchestra Platform with debug as true where 
			// creating/copying the directory would throw an ErrorException.
			if ( ! Extension::isWritableWithAsset($name))
			{
				throw new FilePermissionException("[{$name}] is not writable.");
			}

			call_user_func($callback, $name);
		}
		catch (FilePermissionException $e)
		{
			Publisher::queue($name);

			// In events where extension can't be activated due to 
			// bundle:publish we need to put this under queue.
			return Redirect::to(handles('orchestra/foundation::publisher'));
		}

		return Redirect::to(handles('orchestra/foundation::extensions'));
	}
}
