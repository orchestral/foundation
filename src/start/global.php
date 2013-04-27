<?php

Orchestra\Memory::extend('userMeta', function ($app, $name)
{
	return new Orchestra\Services\UserMetaRepository($app, $name);
});
