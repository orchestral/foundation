<?php namespace Orchestra\Foundation\Bootstrap;

use Illuminate\Support\Facades\Blade;
use Illuminate\Contracts\Foundation\Application;

class LoadExpresso
{
    /**
     * Bootstrap the given application.
     *
     * @param  \Illuminate\Contracts\Foundation\Application  $app
     * @return void
     */
    public function bootstrap(Application $app)
    {
        $this->addBladeExtensions($app);

        $this->addDecoratorExtensions($app);

        $this->addHtmlExtensions($app);
    }

    /**
     * Extends blade compiler.
     *
     * @param  \Illuminate\Contracts\Foundation\Application  $app
     * @return void
     */
    protected function addBladeExtensions(Application $app)
    {
        Blade::extend(function ($view) {
            $decorator = '$1<?php echo app("orchestra.decorator")->render($2); ?>';
            $placeholder = '$1<?php $__ps = app("orchestra.widget")->make("placeholder.".$2); '
                                .'foreach ($__ps as $__p) { echo value($__p->value ?:""); } ?>';

            foreach (compact('decorator', 'placeholder') as $name => $replacement) {
                $view = preg_replace('/(\s*)@'.$name.'\s?\(\s*(.*)\)/', $replacement, $view);
            }

            $view = preg_replace('/(\s*)(<\?\s)/', '$1<?php ', $view);

            return $view;
        });
    }

    /**
     * Add "navbar" macro for "orchestra.decorator" service location.
     *
     * @param  \Illuminate\Contracts\Foundation\Application  $app
     * @return void
     */
    protected function addDecoratorExtensions(Application $app)
    {
        $app['orchestra.decorator']->macro('navbar', function ($navbar) use ($app) {
            return $app['view']->make('orchestra/foundation::components.navbar', compact('navbar'));
        });
    }

    /**
     * Add "title" macros for "html" service location.
     *
     * @param  \Illuminate\Contracts\Foundation\Application  $app
     * @return void
     */
    protected function addHtmlExtensions(Application $app)
    {
        $html = $app['html'];

        $html->macro('title', function () use ($html) {
            $siteTitle = $title = memorize('site.name');
            $pageTitle = trim(get_meta('title', ''));
            $format    = memorize('site.format.title', ':pageTitle &mdash; :siteTitle');

            if (! empty($pageTitle)) {
                $title = strtr($format, [
                    ":siteTitle" => $siteTitle,
                    ":pageTitle" => $pageTitle,
                ]);
            }

            return $html->create('title', $title);
        });
    }
}
