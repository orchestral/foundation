<?php namespace Orchestra\Foundation\Filters;

use Orchestra\Foundation\Foundation;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class RegistrableFilter
{
    /**
     * The application implementation.
     *
     * @var \Orchestra\Foundation\Foundation
     */
    protected $kernel;

    /**
     * Create a new filter instance.
     *
     * @param  \Orchestra\Foundation\Foundation  $kernel
     */
    public function __construct(Foundation $kernel)
    {
        $this->kernel = $kernel;
    }

    /**
     * Run the request filter.
     *
     * @return mixed
     */
    public function filter()
    {
        if (! $this->kernel->memory()->get('site.registrable', false)) {
            throw new NotFoundHttpException('User registration is not available.');
        }
    }
}
