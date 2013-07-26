<?php

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Event;
use Orchestra\Foundation\Services\UserMetaRepository;

App::make('orchestra.memory')->extend('user', function ($app, $name)
{
	return new UserMetaRepository($app, $name);
});

App::bind('Orchestra\Foundation\Installation\InstallerInterface', function ()
{
	return new Orchestra\Foundation\Installation\Installer(App::make('app'));
});

App::bind('Orchestra\Foundation\Installation\RequirementInterface', function ()
{
	return new Orchestra\Foundation\Installation\Requirement(App::make('app'));
});


Event::listen('orchestra.ready', function ()
{
	if ('on' === App::make('session')->get('orchestra.safemode'))
	{
		App::make('orchestra.messages')->add('info', trans('orchestra/foundation::response.safe-mode'));
	}
});
