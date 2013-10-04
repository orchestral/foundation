<?php namespace Orchestra\Foundation\Routing;

use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\View;
use Orchestra\Support\Facades\Site;
use Orchestra\Support\Facades\Widget;

class DashboardController extends AdminController {

	/**
	 * Define the filters.
	 *
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
	 * @return Response
	 */
	public function index()
	{
		Site::set('title', trans("orchestra/foundation::title.home"));

		return View::make('orchestra/foundation::dashboard.index', array(
			'panes' => Widget::make('pane.orchestra'),
		));
	}

	/**
	 * Show missing pages.
	 *
	 * GET (:orchestra) return 404
	 *
	 * @return Response
	 */
	public function missing()
	{
		return $this->missingMethod(array());
	}
}
