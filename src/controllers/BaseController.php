<?php namespace Orchestra\Foundation;

use Illuminate\Routing\Controllers\Controller;

abstract class BaseController extends Controller {

	/**
	 * Use restful verb.
	 * 
	 * @var boolean
	 */
	protected $restful = true;
}