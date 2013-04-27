<?php namespace Orchestra\Foundation;

class RegisterController extends AdminController {
	
	/**
	 * Define the filters.
	 *
	 * @access public
	 * @return void
	 */
	public function __construct()
	{
		parent::__construct();

		// Registration controller should only be accessible if we allow 
		// registration through the setting.
		$this->beforeFilter('orchestra.registrable');
	}
}
