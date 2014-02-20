<?php

use Illuminate\Support\Facades\Route;
use Orchestra\Support\Facades\App;

Route::group(App::group('orchestra/foundation', 'orchestra'), function () {
    // Route to account/profile.
    Route::get('account', 'Orchestra\Foundation\Routing\AccountController@showProfile');
    Route::post('account', 'Orchestra\Foundation\Routing\AccountController@updateProfile');
    Route::get('account/password', 'Orchestra\Foundation\Routing\AccountController@showPassword');
    Route::post('account/password', 'Orchestra\Foundation\Routing\AccountController@updatePassword');

    // Route to extensions.
    if (App::bound('orchestra.extension')) {
        Route::get('extensions', 'Orchestra\Foundation\Routing\ExtensionsController@index');
        Route::get('extensions/activate/{name}', 'Orchestra\Foundation\Routing\ExtensionsController@activate');
        Route::get('extensions/deactivate/{name}', 'Orchestra\Foundation\Routing\ExtensionsController@deactivate');
        Route::get('extensions/update/{name}', 'Orchestra\Foundation\Routing\ExtensionsController@migrate');
        Route::get('extensions/configure/{name}', 'Orchestra\Foundation\Routing\ExtensionsController@configure');
        Route::post('extensions/configure/{name}', 'Orchestra\Foundation\Routing\ExtensionsController@update');
    }

    // Route to reset password.
    Route::get('forgot', 'Orchestra\Foundation\Routing\PasswordBrokerController@index');
    Route::post('forgot', 'Orchestra\Foundation\Routing\PasswordBrokerController@create');
    Route::get('forgot/reset/{token}', 'Orchestra\Foundation\Routing\PasswordBrokerController@show');
    Route::post('forgot/reset', 'Orchestra\Foundation\Routing\PasswordBrokerController@reset');

    // Route to installation.
    Route::get('install', 'Orchestra\Foundation\Routing\InstallerController@index');
    Route::get('install/create', 'Orchestra\Foundation\Routing\InstallerController@create');
    Route::post('install/create', 'Orchestra\Foundation\Routing\InstallerController@store');
    Route::get('install/done', 'Orchestra\Foundation\Routing\InstallerController@done');
    Route::get('install/prepare', 'Orchestra\Foundation\Routing\InstallerController@prepare');

    // Route to asset publishing.
    Route::get('publisher', 'Orchestra\Foundation\Routing\PublisherController@index');
    Route::get('publisher/ftp', 'Orchestra\Foundation\Routing\PublisherController@ftp');
    Route::post('publisher/ftp', 'Orchestra\Foundation\Routing\PublisherController@publish');

    // Route to resources.
    Route::any('resources/{any}', 'Orchestra\Foundation\Routing\ResourcesController@call')->where('any', '(.*)');
    Route::any('resources', 'Orchestra\Foundation\Routing\ResourcesController@index');

    // Route to users.
    Route::resource('users', 'Orchestra\Foundation\Routing\UsersController', array('except' => array('show')));
    Route::any('users/{user}/delete', array('Orchestra\Foundation\Routing\UsersController@delete');

    // Route for settings
    Route::get('settings', 'Orchestra\Foundation\Routing\SettingsController@show');
    Route::post('settings', 'Orchestra\Foundation\Routing\SettingsController@update');
    Route::get('settings/migrate', 'Orchestra\Foundation\Routing\SettingsController@migrate');

    // Route for credentials.
    Route::get('login', 'Orchestra\Foundation\Routing\CredentialController@index');
    Route::post('login', 'Orchestra\Foundation\Routing\CredentialController@login');
    Route::any('logout', 'Orchestra\Foundation\Routing\CredentialController@logout');

    Route::get('register', 'Orchestra\Foundation\Routing\RegistrationController@index');
    Route::post('register', 'Orchestra\Foundation\Routing\RegistrationController@create');

    // Base routing.
    Route::any('/', array(
        'as'     => 'orchestra.dashboard',
        'before' => 'orchestra.installable',
        'uses'   => 'Orchestra\Foundation\Routing\DashboardController@index'
    ));

    // File not found routing.
    Route::any('missing', array(
        'as'   => 'orchestra.404',
        'uses' => 'Orchestra\Foundation\Routing\DashboardController@missing',
    ));
});
