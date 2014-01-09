<?php

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Event;
use Orchestra\Foundation\Installation\Installer;
use Orchestra\Foundation\Installation\Requirement;
use Orchestra\Model\Memory\UserMetaProvider;
use Orchestra\Model\Memory\UserMetaRepository;

App::make('orchestra.memory')->extend('user', function ($app, $name) {
    $handler = new UserMetaRepository($name, array(), $app);

    return new UserMetaProvider($handler);
});

/*
|--------------------------------------------------------------------------
| Bind Installation Interface
|--------------------------------------------------------------------------
|
| These interface allow Orchestra Platform installation process to be
| customized by the application when there a requirement for it.
|
*/

App::bind('Orchestra\Foundation\Installation\InstallerInterface', function () {
    return new Installer(App::make('app'));
});

App::bind('Orchestra\Foundation\Installation\RequirementInterface', function () {
    return new Requirement(App::make('app'));
});

/*
|--------------------------------------------------------------------------
| Inject Safe Mode Notification
|--------------------------------------------------------------------------
|
| This event listener would allow Orchestra Platform to display notification
| if the application is running on safe mode.
|
*/

Event::listen('composing: *', function () {
    if ('on' === App::make('session')->get('orchestra.safemode')) {
        App::make('orchestra.messages')->extend(function ($messages) {
            $messages->add('info', trans('orchestra/foundation::response.safe-mode'));
        });
    }
});

/*
|--------------------------------------------------------------------------
| Inject Auth Roles Detection
|--------------------------------------------------------------------------
|
| We need to ensure that Orchestra\Acl is compliance with our Eloquent Model,
| This would overwrite the default configuration.
|
*/

Event::listen('orchestra.auth: roles', function ($user, $roles) {
    // When user is null, we should expect the roles is not available.
    // Therefore, returning null would propagate any other event listeners
    // (if any) to try resolve the roles.
    if (is_null($user)) {
        return ;
    }

    foreach ($user->roles()->lists('name') as $name) {
        array_push($roles, $name);
    }

    return $roles;
});
