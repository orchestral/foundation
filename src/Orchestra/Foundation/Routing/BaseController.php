<?php namespace Orchestra\Foundation\Routing;

use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Response;

abstract class BaseController extends Controller {

	/**
	 * Show missing pages.
	 *
	 * GET (:orchestra) return 404
	 * 
	 * @param  array    $parameters
	 * @return Response
	 */
	public function missingMethod($parameters)
	{
		return Response::view('orchestra/foundation::dashboard.missing', $parameters, 404);
	}
}
