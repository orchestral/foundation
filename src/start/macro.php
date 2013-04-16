<?php

/*
|--------------------------------------------------------------------------
| Html::title() macro
|--------------------------------------------------------------------------
|
| Page title macro helper.
|
*/

Html::macro('title', function ()
{
	return "<title>Orchestra Platform</title>";
});

/*
|--------------------------------------------------------------------------
| Blade extend for @placeholder
|--------------------------------------------------------------------------
|
| Placeholder is Orchestra Platform version of widget for theme.
|
*/

Blade::extend(function ($view)
{
	$pattern     = '/(\s*)@placeholder\s?\(\s*(.*)\)/';
	$replacement = '$1<?php foreach (Orchestra\Widget::make("placeholder.".$2)->getItem() as $__p): echo value($__p->value ?:""); endforeach; ?>';

	return preg_replace($pattern, $replacement, $view);
});