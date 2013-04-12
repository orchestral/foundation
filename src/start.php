<?php

App::bind('orchestra.memorize', function()
{
	return Orchestra\Memory::make('fluent.orchestra_options');
});