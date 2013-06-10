<?php

use Illuminate\Support\Facades\App;
use Orchestra\Services\UserMetaRepository;

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
