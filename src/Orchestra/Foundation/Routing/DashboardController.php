<?php namespace Orchestra\Foundation\Routing;

use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\View;
use Orchestra\Support\Facades\Widget;

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
		$this->beforeFilter('orchestra.auth', array(
			'only' => array('index'),
		));
	}
	
	/**
	 * Show User Dashboard.
	 *
	 * GET (:orchestra)/
	 *
	 * @access public
	 * @return Response
	 */
	public function index()
	{
		return View::make('orchestra/foundation::dashboard.index')
			->with('panes', Widget::make('pane.orchestra'));
	}

	/**
	 * Show missing pages.
	 *
	 * GET (:orchestra) return 404
	 *
	 * @access public
	 * @return Response
	 */
	public function missing()
	{
		return $this->missingMethod(array());
	}
}
