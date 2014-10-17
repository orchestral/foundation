<?php

use Orchestra\Support\Facades\Foundation;

Foundation::namespaced('Orchestra\Foundation\Routing', function ($router) {
    // Route to account/profile.
    $router->get('account', 'AccountController@showProfile');
    $router->post('account', 'AccountController@updateProfile');
    $router->get('account/password', 'AccountController@showPassword');
    $router->post('account/password', 'AccountController@updatePassword');

    // Route to extensions.
    if (Foundation::bound('orchestra.extension')) {
        $router->get('extensions', 'ExtensionsController@index');
        $router->get('extensions/activate/{name}', 'ExtensionsController@activate');
        $router->get('extensions/deactivate/{name}', 'ExtensionsController@deactivate');
        $router->get('extensions/update/{name}', 'ExtensionsController@migrate');
        $router->get('extensions/configure/{name}', 'ExtensionsController@configure');
        $router->post('extensions/configure/{name}', 'ExtensionsController@update');
    }

    // Route to reset password.
    $router->get('forgot', 'PasswordBrokerController@index');
    $router->post('forgot', 'PasswordBrokerController@create');
    $router->get('forgot/reset/{token}', 'PasswordBrokerController@show');
    $router->post('forgot/reset/{token}', 'PasswordBrokerController@reset');
    $router->post('forgot/reset', 'PasswordBrokerController@reset');

    // Route to installation.
    $router->get('install', 'InstallerController@index');
    $router->get('install/create', 'InstallerController@create');
    $router->post('install/create', 'InstallerController@store');
    $router->get('install/done', 'InstallerController@done');
    $router->get('install/prepare', 'InstallerController@prepare');

    // Route to asset publishing.
    $router->get('publisher', 'PublisherController@index');
    $router->get('publisher/ftp', 'PublisherController@ftp');
    $router->post('publisher/ftp', 'PublisherController@publish');

    // Route to resources.
    if (Foundation::bound('orchestra.resources')) {
        $router->any('resources/{any}', 'ResourcesController@call')->where('any', '(.*)');
        $router->any('resources', 'ResourcesController@index');
    }

    // Route to users.
    $router->resource('users', 'UsersController', ['except' => ['show']]);
    $router->any('users/{user}/delete', 'UsersController@delete');

    // Route for settings
    $router->get('settings', 'SettingsController@show');
    $router->post('settings', 'SettingsController@update');
    $router->get('settings/migrate', 'SettingsController@migrate');

    // Route for credentials.
    $router->get('login', 'CredentialController@index');
    $router->post('login', 'CredentialController@login');
    $router->any('logout', 'CredentialController@logout');

    $router->get('register', 'RegistrationController@index');
    $router->post('register', 'RegistrationController@create');

    // Base routing.
    $router->any('/', [
        'as'     => 'orchestra.dashboard',
        'before' => 'orchestra.installable',
        'uses'   => 'DashboardController@index'
    ]);

    // File not found routing.
    $router->any('missing', [
        'as'   => 'orchestra.404',
        'uses' => 'DashboardController@missing',
    ]);
});
