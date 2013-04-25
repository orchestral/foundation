<?php namespace Orchestra\Foundation;

use Redirect,
	View,
	Orchestra\App,
	Orchestra\Extension,
	Orchestra\Messages,
	Orchestra\Site;

class ExtensionController extends AdminController {

	/**
	 * Construct Extensions Controller, only authenticated user should be
	 * able to access this controller.
	 *
	 * @access public
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
	 * @access public
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
	 * @access public
	 * @param  string   $name   name of the extension
	 * @return Response
	 */
	public function getActivate($name)
	{
		if (Extension::started($name)) return App::abort(404);

		Extension::activate($name);
		Messages::add('success', trans('orchestra/foundation::response.extensions.activate', compact('name')));

		return Redirect::to(handles('orchestra/foundation::extensions'));
	}

	/**
	 * Deactivate an extension.
	 *
	 * GET (:orchestra)/extensions/deactivate/(:name)
	 *
	 * @access public
	 * @param  string   $name   name of the extension
	 * @return Response
	 */
	public function getDeactivate($name)
	{
		if ( ! Extension::started($name) and ! Extension::active($name)) return App::abort(404);
		
		Extension::deactivate($name);
		Messages::add('success', trans('orchestra/foundation::response.extensions.deactivate', compact('name')));

		return Redirect::to(handles('orchestra/foundation::extensions'));
	}
}