<?php

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Event;
use Orchestra\Model\Memory\UserMetaProvider;
use Orchestra\Model\Memory\UserMetaRepository;

App::make('orchestra.memory')->extend('user', function ($app, $name) {
    $handler = new UserMetaRepository($name, array(), $app);

    return new UserMetaProvider($handler);
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
