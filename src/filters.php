<?php

Route::filter('orchestra.installed', function($route, $request, $value = null)
{
	if (App::make('orchestra.installed') === false)
	{
		return Redirect::to(handles('orchestra/foundation::install'));
	}
});

Route::filter('orchestra.not-installed', function($route, $request, $value = null)
{
	if (App::make('orchestra.installed') === true)
	{
		return Redirect::to(handles('orchestra/foundation::/'));
	}
});