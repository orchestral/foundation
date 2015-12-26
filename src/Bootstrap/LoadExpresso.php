<?php namespace Orchestra\Foundation\Bootstrap;

use Orchestra\Html\Macros\Title;
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
        $blade = $app->make('view')->getEngineResolver()->resolve('blade')->getCompiler();

        $blade->directive('decorator', function ($expression) {
            return "<?php echo app('orchestra.decorator')->render{$expression}; ?>";
        });

        $blade->directive('placeholder', function ($expression) {
            $expression = preg_replace('/\(\s*(.*)\)/', '$1', $expression);

            return "<?php \$__ps = app('orchestra.widget')->make('placeholder.'.{$expression}); "
                        ."foreach (\$__ps as \$__p) { echo value(\$__p->value ?: ''); } ?>";
        });

        $blade->directive('get_meta', function ($expression) {
            return "<?php echo get_meta{$expression}; ?>";
        });

        $blade->directive('set_meta', function ($expression) {
            return "<?php set_meta{$expression}; ?>";
        });

        $blade->directive('title', function ($expression) {
            return "<?php echo app('html')->title{$expression}; ?>";
        });

        $blade->extend(function ($view) {
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
        $app->make('orchestra.decorator')->macro('navbar', function ($navbar) use ($app) {
            return $app->make('view')->make('orchestra/foundation::components.navbar', compact('navbar'));
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
        $html = $app->make('html');

        $html->macro('title', function ($title = null) use ($html) {
            $builder = new Title($html, memorize('site.name'), [
                'site' => get_meta('html::title.format.site', '{site.name} (Page {page.number})'),
                'page' => get_meta('html::title.format.page', '{page.title} &mdash; {site.name}'),
            ]);

            return $builder->title($title ?: trim(get_meta('title', '')));
        });
    }
}
