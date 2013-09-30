<?php

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Route;
use Orchestra\Support\Facades\App;

Route::group(App::group('orchestra/foundation', 'orchestra'), function ()
{
	# Route::controller('account', 'Orchestra\Foundation\Routing\AccountController'); #
	Route::get('account', 'Orchestra\Foundation\Routing\AccountController@getIndex');
	Route::post('account', 'Orchestra\Foundation\Routing\AccountController@postIndex');
	Route::get('account/password', 'Orchestra\Foundation\Routing\AccountController@getPassword');
	Route::post('account/password', 'Orchestra\Foundation\Routing\AccountController@postPassword');

	# Route::controller('extensions', 'Orchestra\Foundation\Routing\ExtensionsController'); #
	Route::get('extensions', 'Orchestra\Foundation\Routing\ExtensionsController@getIndex');
	Route::get('extensions/activate/{name}', 'Orchestra\Foundation\Routing\ExtensionsController@getActivate');
	Route::get('extensions/configure/{name}', 'Orchestra\Foundation\Routing\ExtensionsController@getConfigure');
	Route::post('extensions/configure/{name}', 'Orchestra\Foundation\Routing\ExtensionsController@postConfigure');
	Route::get('extensions/deactivate/{name}', 'Orchestra\Foundation\Routing\ExtensionsController@getDeactivate');
	Route::get('extensions/update/{name}', 'Orchestra\Foundation\Routing\ExtensionsController@getUpdate');

	# Route::controller('forgot', 'Orchestra\Foundation\Routing\ForgotController'); #
	Route::get('forgot', 'Orchestra\Foundation\Routing\ForgotController@getIndex');
	Route::post('forgot', 'Orchestra\Foundation\Routing\ForgotController@postIndex');
	Route::get('forgot/reset/{token}', 'Orchestra\Foundation\Routing\ForgotController@getReset');
	Route::post('forgot/reset/{token}', 'Orchestra\Foundation\Routing\ForgotController@postReset');

	# Route::controller('install', 'Orchestra\Foundation\Routing\InstallController'); #
	Route::get('install', 'Orchestra\Foundation\Routing\InstallController@getIndex');
	Route::get('install/create', 'Orchestra\Foundation\Routing\InstallController@getCreate');
	Route::post('install/create', 'Orchestra\Foundation\Routing\InstallController@postCreate');
	Route::get('install/done', 'Orchestra\Foundation\Routing\InstallController@getDone');
	Route::get('install/prepare', 'Orchestra\Foundation\Routing\InstallController@getPrepare');

	# Route::controller('publisher', 'Orchestra\Foundation\Routing\PublisherController'); #
	Route::get('publisher', 'Orchestra\Foundation\Routing\PublisherController@getIndex');
	Route::get('publisher/ftp', 'Orchestra\Foundation\Routing\PublisherController@getFtp');
	Route::post('publisher/ftp', 'Orchestra\Foundation\Routing\PublisherController@postFtp');

	# Route::controller('register', 'Orchestra\Foundation\Routing\RegisterController'); #
	Route::get('register', 'Orchestra\Foundation\Routing\RegisterController@getIndex');
	Route::post('register', 'Orchestra\Foundation\Routing\RegisterController@postIndex');
	
	Route::any('resources{index?}', 'Orchestra\Foundation\Routing\ResourcesController@index')->where('index', '/index');
	Route::any('resources{any}', 'Orchestra\Foundation\Routing\ResourcesController@call')->where('any', '(.*)');
	
	Route::resource('users', 'Orchestra\Foundation\Routing\UsersController', array('except' => array('show')));
	Route::any('users/{id}/delete', 'Orchestra\Foundation\Routing\UsersController@delete');
	
	# Route::controller('settings', 'Orchestra\Foundation\Routing\SettingsController'); #
	Route::get('settings', 'Orchestra\Foundation\Routing\SettingsController@getIndex');
	Route::post('settings', 'Orchestra\Foundation\Routing\SettingsController@postIndex');
	Route::get('settings/update', 'Orchestra\Foundation\Routing\SettingsController@getUpdate');

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
