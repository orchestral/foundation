<?php

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\HTML;
use Illuminate\Support\Facades\View;
use Orchestra\Support\Facades\Site;

/*
|--------------------------------------------------------------------------
| HTML::title() macro
|--------------------------------------------------------------------------
|
| Page title macro helper.
|
*/

HTML::macro('title', function () {
    $siteTitle = $title = memorize('site.name');
    $pageTitle = trim(Site::get('title', ''));
    $format    = memorize('site.format.title', ':pageTitle &mdash; :siteTitle');

    if (! empty($pageTitle)) {
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

Blade::extend(function ($view) {
    $placeholder = '$1<?php $__ps = Orchestra\Support\Facades\Widget::make("placeholder.".$2); '
                        .'foreach ($__ps as $__p) { echo value($__p->value ?:""); } ?>';
    $decorator   = '$1<?php echo Orchestra\Support\Facades\Decorator::render($2); ?>';

    foreach (compact('placeholder', 'decorator') as $name => $replacement) {
        $view = preg_replace('/(\s*)@'.$name.'\s?\(\s*(.*)\)/', $replacement, $view);
    }

    $view = preg_replace('/(\s*)(<\?\s)/', '$1<?php ', $view);

    return $view;
});

/*
|--------------------------------------------------------------------------
| Decorator Macro for Navbar
|--------------------------------------------------------------------------
*/

App::make('orchestra.decorator')->macro('navbar', function ($navbar) {
    return View::make('orchestra/foundation::components.navbar', compact('navbar'));
});
