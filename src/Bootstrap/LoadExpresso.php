<?php namespace Orchestra\Foundation\Bootstrap;

use Illuminate\Pagination\Paginator;
use Orchestra\Support\Str;
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
        $app['html']->macro('title', $this->buildHtmlTitleCallback($app));
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
        return function ($title = null) use ($app) {
            $title = $title ?: trim(get_meta('title', ''));
            $page  = Paginator::resolveCurrentPage();

            $data = [
                'site' => ['name'  => memorize('site.name')],
                'page' => ['title' => $title, 'number' => $page],
            ];

            $data['site']['name'] = $this->getHtmlTitleFormatForSite($data);

            $output = $this->getHtmlTitleFormatForPage($data);

            return $app['html']->create('title', trim($output));
        };
    }

    /**
     * Get HTML::title() format for site.
     *
     * @param  array  $data
     *
     * @return mixed
     */
    protected function getHtmlTitleFormatForSite($data)
    {
        if ((int) $data['page']['number'] < 2) {
            return $data['site']['name'];
        }

        $format = memorize('site.format.title.site', '{site.name} (Page {page.number})');

        return Str::replace($format, $data);
    }

    /**
     * Get HTML::title() format for page.
     *
     * @param  array  $data
     *
     * @return mixed
     */
    protected function getHtmlTitleFormatForPage(array $data)
    {
        if (empty($data['page']['title'])) {
            return $data['site']['name'];
        }

        $format = memorize('site.format.title.page', '{page.title} &mdash; {site.name}');

        return Str::replace($format, $data);
    }
}
