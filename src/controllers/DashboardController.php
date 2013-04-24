<?php namespace Orchestra\Foundation;

use Illuminate\Support\Facades\View,
	Orchestra\Widget;

class DashboardController extends AdminController {

	/**
	 * Define the filters.
	 *
	 * @access public
	 * @return void
	 */
	public function __construct()
	{
		parent::__construct();

		// User has to be authenticated before using this controller.
		$this->beforeFilter('orchestra.auth');
	}
	
	public function anyIndex()
	{
		return View::make('orchestra/foundation::dashboard.index')
			->with('panes', Widget::make('pane.orchestra')->getItem());
	}
}