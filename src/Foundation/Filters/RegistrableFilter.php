<?php namespace Orchestra\Foundation\Filters;

use Orchestra\Foundation\Application;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class RegistrableFilter
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
        if (! $this->app->memory()->get('site.registrable', false)) {
            throw new NotFoundHttpException('User registration is not available.');
        }
    }
}
