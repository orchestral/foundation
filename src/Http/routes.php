<?php

use Illuminate\Routing\Router;
use Orchestra\Support\Facades\Foundation;

Foundation::namespaced('Orchestra\Foundation\Http\Controllers', function (Router $router) {
    // Route to account/profile.
    $router->get('account', 'Account\ProfileUpdaterController@edit');
    $router->post('account', 'Account\ProfileUpdaterController@update');
    $router->get('account/password', 'Account\PasswordUpdaterController@edit');
    $router->post('account/password', 'Account\PasswordUpdaterController@update');

    // Route to extensions.
    if (Foundation::bound('orchestra.extension')) {
        $router->get('extensions', 'Extension\ViewerController@index');

        $router->post('extensions/{vendor}/{package}/activate', 'Extension\ActionController@activate');
        $router->post('extensions/{vendor}/activate', 'Extension\ActionController@activate');

        $router->post('extensions/{vendor}/{package}/deactivate', 'Extension\ActionController@deactivate');
        $router->post('extensions/{vendor}/deactivate', 'Extension\ActionController@deactivate');

        $router->post('extensions/{vendor}/{package}/update', 'Extension\ActionController@migrate');
        $router->post('extensions/{vendor}/update', 'Extension\ActionController@migrate');

        $router->get('extensions/{vendor}/{package}/configure', 'Extension\ConfigureController@configure');
        $router->get('extensions/{vendor}/configure', 'Extension\ConfigureController@configure');
        $router->post('extensions/{vendor}/{package}/configure', 'Extension\ConfigureController@update');
        $router->post('extensions/{vendor}/configure', 'Extension\ConfigureController@update');
    }

    // Route to request reset password.
    $router->get('forgot', 'Account\PasswordBrokerController@showLinkRequestForm');
    $router->post('forgot', 'Account\PasswordBrokerController@sendResetLinkEmail');

    // Route to reset password.
    $router->get('forgot/reset/{token?}', 'Account\PasswordBrokerController@showResetForm');
    $router->post('forgot/reset', 'Account\PasswordBrokerController@reset');

    // Route to asset publishing.
    $router->get('publisher', 'PublisherController@show');
    $router->get('publisher/ftp', 'PublisherController@ftp');
    $router->post('publisher/ftp', 'PublisherController@publish');

    // Route to users.
    $router->resource('users', 'UsersController', ['except' => ['show']]);

    // Route for settings
    $router->get('settings', 'SettingsController@edit');
    $router->post('settings', 'SettingsController@update');
    $router->get('settings/migrate', 'SettingsController@migrate');

    // Route for credentials.
    $router->get('login', 'CredentialController@show');
    $router->post('login', 'CredentialController@login');
    $router->delete('logout', 'CredentialController@logout');

    $router->get('register', 'Account\ProfileCreatorController@create');
    $router->post('register', 'Account\ProfileCreatorController@store');

    $router->get('sudo', 'Account\RequthenticateController@show');
    $router->post('sudo', 'Account\RequthenticateController@reauth');

    // Base routing.
    $router->match(['GET', 'HEAD'], '/', [
        'as'     => 'orchestra.dashboard',
        'before' => 'orchestra.installable',
        'uses'   => 'DashboardController@show',
    ]);

    // File not found routing.
    $router->any('missing', [
        'as'   => 'orchestra.404',
        'uses' => 'DashboardController@missing',
    ]);
});
