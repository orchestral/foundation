<?php

Route::group(array('prefix' => Config::get('orchestra/foundation::handles', 'orchestra')), function ()
{
	Route::controller('account', 'Orchestra\Foundation\AccountController');
	Route::controller('extensions', 'Orchestra\Foundation\ExtensionsController');
	Route::controller('forgot', 'Orchestra\Foundation\ForgotController');
	Route::controller('install', 'Orchestra\Foundation\InstallController');
	Route::controller('register', 'Orchestra\Foundation\RegisterController');
	Route::controller('resources', 'Orchestra\Foundation\ResourceController');
	Route::resource('users', 'Orchestra\Foundation\UsersController');
	Route::any('users/{id}/delete', 'Orchestra\Foundation\UsersController@delete');
	Route::controller('settings', 'Orchestra\Foundation\SettingsController');

	// Credential routing.
	Route::get('login', 'Orchestra\Foundation\CredentialController@getLogin');
	Route::post('login', 'Orchestra\Foundation\CredentialController@postLogin');
	Route::any('logout', 'Orchestra\Foundation\CredentialController@deleteLogin');
	
	// Base routing.
	Route::any('/', array(
		'before' => 'orchestra.installable',
		'uses'   => 'Orchestra\Foundation\DashboardController@anyIndex'
	));

	// 404 routing.
	Route::any('missing', array(
		'as'   => 'orchestra.404',
		'uses' => 'Orchestra\Foundation\DashboardController@anyMissing',
	));
});