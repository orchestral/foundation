<?php

Route::group(array('prefix' => Config::get('orchestra/foundation::handles', 'orchestra')), function ()
{
	Route::controller('install', 'Orchestra\Foundation\InstallController');
	
	Route::any('/', array(
		'before' => 'orchestra.installable',
		'uses'   => 'Orchestra\Foundation\DashboardController@anyIndex'
	));
});