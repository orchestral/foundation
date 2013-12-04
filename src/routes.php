<?php

use Illuminate\Support\Facades\Route;
use Orchestra\Support\Facades\App;

Route::group(App::group('orchestra/foundation', 'orchestra'), function () {
    // Route to account/profile.
    Route::get('account', 'Orchestra\Foundation\Routing\AccountController@getProfile');
    Route::post('account', 'Orchestra\Foundation\Routing\AccountController@postProfile');
    Route::get('account/password', 'Orchestra\Foundation\Routing\AccountController@getPassword');
    Route::post('account/password', 'Orchestra\Foundation\Routing\AccountController@postPassword');

    // Route to extensions.
    Route::get('extensions', 'Orchestra\Foundation\Routing\ExtensionsController@getIndex');
    Route::get('extensions/activate/{name}', 'Orchestra\Foundation\Routing\ExtensionsController@getActivate');
    Route::get('extensions/configure/{name}', 'Orchestra\Foundation\Routing\ExtensionsController@getConfigure');
    Route::post('extensions/configure/{name}', 'Orchestra\Foundation\Routing\ExtensionsController@postConfigure');
    Route::get('extensions/deactivate/{name}', 'Orchestra\Foundation\Routing\ExtensionsController@getDeactivate');
    Route::get('extensions/update/{name}', 'Orchestra\Foundation\Routing\ExtensionsController@getUpdate');

    // Route to reset password.
    Route::get('forgot', 'Orchestra\Foundation\Routing\PasswordBrokerController@getIndex');
    Route::post('forgot', 'Orchestra\Foundation\Routing\PasswordBrokerController@postIndex');
    Route::get('forgot/reset/{token}', 'Orchestra\Foundation\Routing\PasswordBrokerController@getReset');
    Route::post('forgot/reset', 'Orchestra\Foundation\Routing\PasswordBrokerController@postReset');

    // Route to installation.
    Route::get('install', 'Orchestra\Foundation\Routing\InstallController@getIndex');
    Route::get('install/create', 'Orchestra\Foundation\Routing\InstallController@getCreate');
    Route::post('install/create', 'Orchestra\Foundation\Routing\InstallController@postCreate');
    Route::get('install/done', 'Orchestra\Foundation\Routing\InstallController@getDone');
    Route::get('install/prepare', 'Orchestra\Foundation\Routing\InstallController@getPrepare');

    // Route to asset publishing.
    Route::get('publisher', 'Orchestra\Foundation\Routing\PublisherController@getIndex');
    Route::get('publisher/ftp', 'Orchestra\Foundation\Routing\PublisherController@getFtp');
    Route::post('publisher/ftp', 'Orchestra\Foundation\Routing\PublisherController@postFtp');

    // Route to resources.
    Route::any('resources/{any}', 'Orchestra\Foundation\Routing\ResourcesController@call')->where('any', '(.*)');
    Route::any('resources', 'Orchestra\Foundation\Routing\ResourcesController@index');

    // Route to users.
    Route::resource('users', 'Orchestra\Foundation\Routing\UsersController', array('except' => array('show')));
    Route::any('users/{id}/delete', 'Orchestra\Foundation\Routing\UsersController@delete');

    // Route for settings
    Route::get('settings', 'Orchestra\Foundation\Routing\SettingsController@getIndex');
    Route::post('settings', 'Orchestra\Foundation\Routing\SettingsController@postIndex');
    Route::get('settings/migrate', 'Orchestra\Foundation\Routing\SettingsController@getMigrate');

    // Route for credentials.
    Route::get('login', 'Orchestra\Foundation\Routing\CredentialController@getLogin');
    Route::post('login', 'Orchestra\Foundation\Routing\CredentialController@postLogin');
    Route::any('logout', 'Orchestra\Foundation\Routing\CredentialController@deleteLogin');
    Route::get('register', 'Orchestra\Foundation\Routing\RegisterController@getIndex');
    Route::post('register', 'Orchestra\Foundation\Routing\RegisterController@postIndex');

    // Base routing.
    Route::any('/', array(
        'before' => 'orchestra.installable',
        'uses'   => 'Orchestra\Foundation\Routing\DashboardController@index'
    ));

    // File not found routing.
    Route::any('missing', array(
        'as'   => 'orchestra.404',
        'uses' => 'Orchestra\Foundation\Routing\DashboardController@missing',
    ));
});
