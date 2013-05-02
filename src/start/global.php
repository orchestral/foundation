<?php

Orchestra\Memory::extend('user', function ($app, $name)
{
	return new Orchestra\Services\UserMetaRepository($app, $name);
});
