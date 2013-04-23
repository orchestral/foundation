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
	$siteTitle = $title = memorize('site.name');
	$pageTitle = trim(Orchestra\Site::get('title', ''));
	$format    = memorize('site.format.title', ':pageTitle &mdash; :siteTitle');

	if ( ! empty($page_title)) 
	{
		$title = strtr($format, array(
			":siteTitle" => $siteTitle,
			":pageTitle" => $pageTitle,
		));
	}

	return Html::create('title', $title);
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

/*
|--------------------------------------------------------------------------
| Decorator Macro for Navbar
|--------------------------------------------------------------------------
*/

Orchestra\Decorator::macro('navbar', function ($navbar)
{
	return View::make('orchestra/foundation::layout.widgets.navbar', compact('navbar'));
});