<?php namespace Orchestra\Foundation\Routing;

use Illuminate\Support\Facades\Event;

abstract class AdminController extends BaseController {

	/**
	 * Base construct method.
	 */
	public function __construct()
	{
		// Admin controllers should be accessible only after 
		// Orchestra Platform is installed.
		$this->beforeFilter('orchestra.installable');
		
		$this->beforeFilter(function ()
		{
			Event::fire('orchestra.started: admin');
			Event::fire('orchestra.ready: admin');
		});

		$this->afterFilter(function ()
		{
			Event::fire('orchestra.done: admin');
		});

		parent::__construct();
	}

	/**
	 * Setup controller filters.
	 *
	 * @return void
	 */
	protected function setupFilters() {}
}
