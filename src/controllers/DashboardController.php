<?php namespace Orchestra\Foundation;

use Illuminate\Support\Facades\View;

class DashboardController extends AdminController {
	
	public function anyIndex()
	{
		return 'in dashboard';
	}
}