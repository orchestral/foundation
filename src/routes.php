<?php

Route::group(array('prefix' => Config::get('orchestra/foundation::handles', 'orchestra')), function ()
{
	Route::controller('install', 'Orchestra\Foundation\InstallController');

	// Credential routing.
	Route::get('login', 'Orchestra\Foundation\CredentialController@getLogin');
	Route::post('login', 'Orchestra\Foundation\CredentialController@postLogin');
	Route::get('register', 'Orchestra\Foundation\CredentialController@getRegister');
	Route::post('register', 'Orchestra\Foundation\CredentialController@postRegister');
	Route::any('logout', 'Orchestra\Foundation\CredentialController@deleteLogin');
	
	// Base routing.
	Route::any('/', array(
		'before' => 'orchestra.installable',
		'uses'   => 'Orchestra\Foundation\DashboardController@anyIndex'
	));

	// 404 routing.
	Route::any('{missing}', array(
		'as'   => 'orchestra.404',
		'uses' => 'Orchestra\Foundation\DashboardController@anyMissing',
	));
});