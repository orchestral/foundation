<?php

use Illuminate\Support\Facades\App,
	Illuminate\Support\Facades\Event;

App::make('orchestra.app')->boot();
App::make('orchestra.site')->boot();

include_once __DIR__.'/start/global.php';
include_once __DIR__.'/start/macros.php';
include_once __DIR__.'/start/events.php';
include_once __DIR__.'/filters.php';
include_once __DIR__.'/routes.php';

Event::fire('orchestra.ready');
