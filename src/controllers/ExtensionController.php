<?php namespace Orchestra\Foundation;

use View,
	Orchestra\Extension,
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
}