<?php namespace Orchestra\Foundation;

use Illuminate\Support\Facades\View;

class InstallController extends BaseController {
	
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

	public function getSchema()
	{
		$app       = app();
		$migration = new Publisher\Migration(app());
	}
}