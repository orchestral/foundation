<?php

Route::group(array('prefix' => Config::get('orchestra/foundation::handles', 'orchestra')), function ()
{
	Route::controller('account', 'Orchestra\AccountController');
	Route::controller('extensions', 'Orchestra\ExtensionsController');
	Route::controller('forgot', 'Orchestra\ForgotController');
	Route::controller('install', 'Orchestra\InstallController');
	Route::controller('publisher', 'Orchestra\PublisherController');
	Route::controller('register', 'Orchestra\RegisterController');
	Route::any('resources{index?}', 'Orchestra\ResourcesController@anyIndex')->where('index', '/index');
	Route::any('resources{any}', 'Orchestra\ResourcesController@call')->where('any', '(.*)');
	Route::resource('users', 'Orchestra\UsersController');
	Route::any('users/{id}/delete', 'Orchestra\UsersController@delete');
	Route::controller('settings', 'Orchestra\SettingsController');

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
