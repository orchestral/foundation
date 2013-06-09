<?php

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Event;

App::make('orchestra.app')->boot();

include __DIR__.'/start/global.php';
include __DIR__.'/start/macros.php';
include __DIR__.'/start/events.php';
include __DIR__.'/filters.php';
include __DIR__.'/routes.php';

Event::fire('orchestra.ready');
