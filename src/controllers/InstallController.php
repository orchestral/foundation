<?php namespace Orchestra\Foundation;

use Illuminate\Support\Facades\View;

class InstallController extends \Controller {

	protected $restful = true;
	
	public function anyIndex()
	{
		$data = array();
		return View::make('orchestra/foundation::install.index', $data);
	}
}