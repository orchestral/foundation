<?php

Route::group(array('prefix' => Config::get('orchestra/foundation::handles', 'orchestra')), function ()
{
	Route::controller('installer', 'Orchestra\Foundation\InstallController');
	Route::any('/', array(
		'before' => 'orchestra.installed',
		'uses'   => 'Orchestra\Foundation\DashboardController@anyIndex'
	));
});