<?php namespace Orchestra\Foundation;

use Controller;
use Response;

abstract class BaseController extends Controller {

	/**
	 * Show missing pages.
	 *
	 * GET (:orchestra) return 404
	 *
	 * @access public
	 * @return Response
	 */
	public function missingMethod($parameters)
	{
		return Response::view('orchestra/foundation::dashboard.missing', array(), 404);
	}
}
