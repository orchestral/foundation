<?php namespace Orchestra\Foundation\Bootstrap;

use Illuminate\Contracts\Foundation\Application;

class LoadExpresso
{
    /**
     * Bootstrap the given application.
     *
     * @param  \Illuminate\Contracts\Foundation\Application  $app
     *
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
     *
     * @return void
     */
    protected function addBladeExtensions(Application $app)
    {
        $compiler = $app['view']->getEngineResolver()->resolve('blade')->getCompiler();

        $compiler->extend(function ($view) {
            $expression = [
                'decorator'   => '$1<?php echo app("orchestra.decorator")->render($2); ?>',
                'placeholder' => '$1<?php $__ps = app("orchestra.widget")->make("placeholder.".$2); '
                                .'foreach ($__ps as $__p) { echo value($__p->value ?:""); } ?>',
                'get_meta' => '$1<?php echo get_meta($2); ?>',
                'set_meta' => '$1<?php set_meta($2); ?>',
                'title'    => '$1<?php echo app("html")->title($2); ?>',
            ];

            foreach ($expression as $name => $replacement) {
                $view = preg_replace('/(\s*)@'.$name.'\s?\(\s*(.*)\)/', $replacement, $view);
            }

            $view = preg_replace('/(\s*)(<\?\s)/', '$1<?php ', $view);
            $view = preg_replace('/#\{\{\s*(.+?)\s*\}\}/s', '<?php $1; ?>', $view);

            return $view;
        });
    }

    /**
     * Add "navbar" macro for "orchestra.decorator" service location.
     *
     * @param  \Illuminate\Contracts\Foundation\Application  $app
     *
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
     *
     * @return void
     */
    protected function addHtmlExtensions(Application $app)
    {
        $html = $app['html'];

        $html->macro('title', function ($pageTitle = null) use ($html) {
            $siteTitle = $title = memorize('site.name');
            $pageTitle = $pageTitle ?: trim(get_meta('title', ''));
            $format    = memorize('site.format.title', ':pageTitle &mdash; :siteTitle');

            if (! empty($pageTitle)) {
                $title = strtr($format, [
                    ':siteTitle' => $siteTitle,
                    ':pageTitle' => $pageTitle,
                ]);
            }

            return $html->create('title', $title);
        });
    }
}
