<?php namespace Orchestra\Foundation;

use Illuminate\Support\Facades\View,
	Orchestra\Support\Facades\App,
	Orchestra\Support\Facades\Site;

class InstallController extends BaseController {

	/**
	 * Construct InstallController
	 *
	 * @access public
	 * @return void
	 */
	public function __construct()
	{
		Site::set('navigation::usernav', false);
		Site::set('title', 'Installer');
		App::memory()->put('site.name', 'Orchestra Platform');
	}
	
	/**
	 * Check installation requirement page.
	 *
	 * GET (:orchestra)/installer
	 *
	 * @access public
	 * @return View
	 */
	public function anyIndex()
	{
		$data = array();
		return View::make('orchestra/foundation::install.index', $data);
	}
}