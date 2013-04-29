<?php namespace Orchestra\Routing;

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
			'only' => array('anyIndex'),
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
	public function anyIndex()
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
	public function anyMissing()
	{
		return $this->missingMethod(array());
	}
}
