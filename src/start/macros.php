<?php

/*
|--------------------------------------------------------------------------
| HTML::title() macro
|--------------------------------------------------------------------------
|
| Page title macro helper.
|
*/

HTML::macro('title', function ()
{
	$siteTitle = $title = memorize('site.name');
	$pageTitle = trim(Orchestra\Site::get('title', ''));
	$format    = memorize('site.format.title', ':pageTitle &mdash; :siteTitle');

	if ( ! empty($pageTitle)) 
	{
		$title = strtr($format, array(
			":siteTitle" => $siteTitle,
			":pageTitle" => $pageTitle,
		));
	}

	return HTML::create('title', $title);
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
	$replacement = '$1<?php $__ps = Orchestra\Widget::make("placeholder.".$2); '
		.'foreach ($__ps as $__p) { echo value($__p->value ?:""); } ?>';

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
