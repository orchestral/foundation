<?php

use Orchestra\Support\Facades\Foundation;

Foundation::namespaced('Orchestra\Foundation\Routing', function ($router) {
    // Route to account/profile.
    $router->get('account', 'Account\ProfileUpdaterController@edit');
    $router->post('account', 'Account\ProfileUpdaterController@update');
    $router->get('account/password', 'Account\PasswordUpdaterController@edit');
    $router->post('account/password', 'Account\PasswordUpdaterController@update');

    // Route to extensions.
    if (Foundation::bound('orchestra.extension')) {
        $router->get('extensions', 'ExtensionsController@index');
        $router->get('extensions/activate/{name}', 'Extension\ActivatorController@activate');
        $router->get('extensions/deactivate/{name}', 'ExtensionsController@deactivate');
        $router->get('extensions/update/{name}', 'ExtensionsController@migrate');
        $router->get('extensions/configure/{name}', 'ExtensionsController@configure');
        $router->post('extensions/configure/{name}', 'ExtensionsController@update');
    }

    // Route to reset password.
    $router->get('forgot', 'Account\PasswordBrokerController@create');
    $router->post('forgot', 'Account\PasswordBrokerController@store');
    $router->get('forgot/reset/{token}', 'Account\PasswordBrokerController@show');
    $router->post('forgot/reset/{token}', 'Account\PasswordBrokerController@show');
    $router->post('forgot/reset', 'Account\PasswordBrokerController@update');

    // Route to asset publishing.
    $router->get('publisher', 'PublisherController@index');
    $router->get('publisher/ftp', 'PublisherController@ftp');
    $router->post('publisher/ftp', 'PublisherController@publish');

    // Route to resources.
    $router->any('resources/{any}', 'ResourcesController@show')->where('any', '(.*)');
    $router->any('resources', 'ResourcesController@index');

    // Route to users.
    $router->resource('users', 'UsersController', ['except' => ['show']]);
    $router->any('users/{user}/delete', 'UsersController@delete');

    // Route for settings
    $router->get('settings', 'SettingsController@edit');
    $router->post('settings', 'SettingsController@update');
    $router->get('settings/migrate', 'SettingsController@migrate');

    // Route for credentials.
    $router->get('login', 'CredentialController@index');
    $router->post('login', 'CredentialController@login');
    $router->any('logout', 'CredentialController@logout');

    $router->get('register', 'Account\ProfileCreatorController@create');
    $router->post('register', 'Account\ProfileCreatorController@store');

    // Base routing.
    $router->any('/', [
        'as'     => 'orchestra.dashboard',
        'before' => 'orchestra.installable',
        'uses'   => 'DashboardController@show'
    ]);

    // File not found routing.
    $router->any('missing', [
        'as'   => 'orchestra.404',
        'uses' => 'DashboardController@missing',
    ]);
});
