<?php namespace Orchestra\Foundation\Filters;

use Illuminate\Http\RedirectResponse;
use Orchestra\Foundation\Application;

class InstallableFilter
{
    /**
     * The application implementation.
     *
     * @var \Orchestra\Foundation\Application
     */
    protected $app;

    /**
     * Create a new filter instance.
     *
     * @param  \Orchestra\Foundation\Application    $app
     */
    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    /**
     * Run the request filter.
     *
     * @return mixed
     */
    public function filter()
    {
        if (! $this->app->installed()) {
            return new RedirectResponse(handles('orchestra::install'));
        }
    }
}
