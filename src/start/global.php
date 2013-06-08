<?php

use Illuminate\Support\Facades\App;
use Orchestra\Services\UserMetaRepository;

App::make('orchestra.memory')->extend('user', function ($app, $name)
{
	return new UserMetaRepository($app, $name);
});
