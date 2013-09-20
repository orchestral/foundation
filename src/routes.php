<?php

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Route;
use Orchestra\Support\Facades\App;

Route::group(App::group('orchestra/foundation', 'orchestra'), function ()
{
	Route::controller('account', 'Orchestra\Foundation\Routing\AccountController');
	Route::controller('extensions', 'Orchestra\Foundation\Routing\ExtensionsController');
	Route::controller('forgot', 'Orchestra\Foundation\Routing\ForgotController');
	Route::controller('install', 'Orchestra\Foundation\Routing\InstallController');
	Route::controller('publisher', 'Orchestra\Foundation\Routing\PublisherController');
	Route::controller('register', 'Orchestra\Foundation\Routing\RegisterController');
	Route::any('resources{index?}', 'Orchestra\Foundation\Routing\ResourcesController@index')->where('index', '/index');
	Route::any('resources{any}', 'Orchestra\Foundation\Routing\ResourcesController@call')->where('any', '(.*)');
	Route::resource('users', 'Orchestra\Foundation\Routing\UsersController', array('except' => array('show')));
	Route::any('users/{id}/delete', 'Orchestra\Foundation\Routing\UsersController@delete');
	Route::controller('settings', 'Orchestra\Foundation\Routing\SettingsController');

	// Credential routing.
	Route::get('login', 'Orchestra\Foundation\Routing\CredentialController@getLogin');
	Route::post('login', 'Orchestra\Foundation\Routing\CredentialController@postLogin');
	Route::any('logout', 'Orchestra\Foundation\Routing\CredentialController@deleteLogin');
	
	// Base routing.
	Route::any('/', array(
		'before' => 'orchestra.installable',
		'uses'   => 'Orchestra\Foundation\Routing\DashboardController@index'
	));

	// 404 routing.
	Route::any('missing', array(
		'as'   => 'orchestra.404',
		'uses' => 'Orchestra\Foundation\Routing\DashboardController@missing',
	));
});
