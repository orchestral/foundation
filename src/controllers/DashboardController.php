<?php namespace Orchestra\Foundation;

use Illuminate\Support\Facades\View;

class DashboardController extends \Controller {

	protected $restful = true;
	
	public function anyIndex()
	{
		return 'in dashboard';
	}
}