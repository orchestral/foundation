<?php

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Route;

Route::group(array('prefix' => Config::get('orchestra/foundation::handles', 'orchestra')), function ()
{
	Route::controller('account', 'Orchestra\Routing\AccountController');
	Route::controller('extensions', 'Orchestra\Routing\ExtensionsController');
	Route::controller('forgot', 'Orchestra\Routing\ForgotController');
	Route::controller('install', 'Orchestra\Routing\InstallController');
	Route::controller('publisher', 'Orchestra\Routing\PublisherController');
	Route::controller('register', 'Orchestra\Routing\RegisterController');
	Route::any('resources{index?}', 'Orchestra\Routing\ResourcesController@index')->where('index', '/index');
	Route::any('resources{any}', 'Orchestra\Routing\ResourcesController@call')->where('any', '(.*)');
	Route::resource('users', 'Orchestra\Routing\UsersController');
	Route::any('users/{id}/delete', 'Orchestra\Routing\UsersController@delete');
	Route::controller('settings', 'Orchestra\Routing\SettingsController');

	// Credential routing.
	Route::get('login', 'Orchestra\Routing\CredentialController@getLogin');
	Route::post('login', 'Orchestra\Routing\CredentialController@postLogin');
	Route::any('logout', 'Orchestra\Routing\CredentialController@deleteLogin');
	
	// Base routing.
	Route::any('/', array(
		'before' => 'orchestra.installable',
		'uses'   => 'Orchestra\Routing\DashboardController@index'
	));

	// 404 routing.
	Route::any('missing', array(
		'as'   => 'orchestra.404',
		'uses' => 'Orchestra\Routing\DashboardController@missing',
	));
});
