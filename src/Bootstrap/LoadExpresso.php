<?php namespace Orchestra\Foundation\Bootstrap;

use Orchestra\Support\Str;
use Illuminate\Pagination\Paginator;
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
        $app->make('html')->macro('title', $this->buildHtmlTitleCallback($app));
    }

    /**
     * Build HTML::title() callback.
     *
     * @param  \Illuminate\Contracts\Foundation\Application  $app
     *
     * @return callable
     */
    protected function buildHtmlTitleCallback(Application $app)
    {
        $me = $this;

        return function ($title = null) use ($app, $me) {
            $title = $title ?: trim(get_meta('title', ''));
            $page  = Paginator::resolveCurrentPage();

            $data = [
                'site' => ['name'  => memorize('site.name')],
                'page' => ['title' => $title, 'number' => $page],
            ];

            $data['site']['name'] = $me->getHtmlTitleFormatForSite($data);

            $output = $me->getHtmlTitleFormatForPage($data);

            return $app->make('html')->create('title', trim($output));
        };
    }

    /**
     * Get HTML::title() format for site.
     *
     * @param  array  $data
     *
     * @return mixed
     */
    public function getHtmlTitleFormatForSite(array $data)
    {
        if ((int) $data['page']['number'] < 2) {
            return $data['site']['name'];
        }

        $format = get_meta('html::title.format.site', '{site.name} (Page {page.number})');

        return Str::replace($format, $data);
    }

    /**
     * Get HTML::title() format for page.
     *
     * @param  array  $data
     *
     * @return mixed
     */
    public function getHtmlTitleFormatForPage(array $data)
    {
        if (empty($data['page']['title'])) {
            return $data['site']['name'];
        }

        $format = get_meta('html::title.format.page', '{page.title} &mdash; {site.name}');

        return Str::replace($format, $data);
    }
}
