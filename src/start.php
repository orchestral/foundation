<?php

App::bind('orchestra.foundation: memory', function()
{
	return Orchestra\Memory::make('fluent.orchestra_options');
});