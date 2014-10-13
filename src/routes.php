<?php

use Illuminate\Support\Facades\Route;
use Orchestra\Support\Facades\App;

App::namespaced('Orchestra\Foundation\Routing', function () {
    // Route to account/profile.
    Route::get('account', 'AccountController@showProfile');
    Route::post('account', 'AccountController@updateProfile');
    Route::get('account/password', 'AccountController@showPassword');
    Route::post('account/password', 'AccountController@updatePassword');

    // Route to extensions.
    if (App::bound('orchestra.extension')) {
        Route::get('extensions', 'ExtensionsController@index');
        Route::get('extensions/activate/{name}', 'ExtensionsController@activate');
        Route::get('extensions/deactivate/{name}', 'ExtensionsController@deactivate');
        Route::get('extensions/update/{name}', 'ExtensionsController@migrate');
        Route::get('extensions/configure/{name}', 'ExtensionsController@configure');
        Route::post('extensions/configure/{name}', 'ExtensionsController@update');
    }

    // Route to reset password.
    Route::get('forgot', 'PasswordBrokerController@index');
    Route::post('forgot', 'PasswordBrokerController@create');
    Route::get('forgot/reset/{token}', 'PasswordBrokerController@show');
    Route::post('forgot/reset/{token}', 'PasswordBrokerController@reset');
    Route::post('forgot/reset', 'PasswordBrokerController@reset');

    // Route to installation.
    Route::get('install', 'InstallerController@index');
    Route::get('install/create', 'InstallerController@create');
    Route::post('install/create', 'InstallerController@store');
    Route::get('install/done', 'InstallerController@done');
    Route::get('install/prepare', 'InstallerController@prepare');

    // Route to asset publishing.
    Route::get('publisher', 'PublisherController@index');
    Route::get('publisher/ftp', 'PublisherController@ftp');
    Route::post('publisher/ftp', 'PublisherController@publish');

    // Route to resources.
    Route::any('resources/{any}', 'ResourcesController@call')->where('any', '(.*)');
    Route::any('resources', 'ResourcesController@index');

    // Route to users.
    Route::resource('users', 'UsersController', ['except' => ['show']]);
    Route::any('users/{user}/delete', 'UsersController@delete');

    // Route for settings
    Route::get('settings', 'SettingsController@show');
    Route::post('settings', 'SettingsController@update');
    Route::get('settings/migrate', 'SettingsController@migrate');

    // Route for credentials.
    Route::get('login', 'CredentialController@index');
    Route::post('login', 'CredentialController@login');
    Route::any('logout', 'CredentialController@logout');

    Route::get('register', 'RegistrationController@index');
    Route::post('register', 'RegistrationController@create');

    // Base routing.
    Route::any('/', [
        'as'     => 'orchestra.dashboard',
        'before' => 'orchestra.installable',
        'uses'   => 'DashboardController@index'
    ]);

    // File not found routing.
    Route::any('missing', [
        'as'   => 'orchestra.404',
        'uses' => 'DashboardController@missing',
    ]);
});
