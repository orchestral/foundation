<?php

App::bind('orchestra.memory', function ()
{
	return new Orchestra\Memory::make('fluent');
});
