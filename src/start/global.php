<?php

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Event;
use Orchestra\Support\Messages;
use Orchestra\Foundation\Services\UserMetaRepository;

App::make('orchestra.memory')->extend('user', function ($app, $name)
{
	return new UserMetaRepository($app, $name);
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

App::bind('Orchestra\Foundation\Installation\InstallerInterface', function ()
{
	return new Orchestra\Foundation\Installation\Installer(App::make('app'));
});

App::bind('Orchestra\Foundation\Installation\RequirementInterface', function ()
{
	return new Orchestra\Foundation\Installation\Requirement(App::make('app'));
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

Event::listen('composing: *', function ()
{
	if ('on' === App::make('session')->get('orchestra.safemode'))
	{
		App::make('orchestra.messages')->extend(function ($messages)
		{
			$messages->add('info', trans('orchestra/foundation::response.safe-mode'));
		});
	}
});
