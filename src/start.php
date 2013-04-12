<?php

App::bind('orchestra.memory', function ()
{
	return Orchestra\Memory::make('fluent');
});
