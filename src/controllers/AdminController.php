<?php namespace Orchestra\Foundation;

abstract class AdminController extends BaseController {

	/**
	 * Define the filters.
	 *
	 * @access public
	 * @return void
	 */
	public function __construct()
	{
		// Admin controllers should be accessible only after 
		// Orchestra Platform is installed.
		$this->filter('before', 'orchestra.installable');

		Event::fire('orchestra.started: admin');
	}
}