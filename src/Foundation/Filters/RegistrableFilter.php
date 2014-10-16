<?php namespace Orchestra\Foundation\Filters;

use Orchestra\Foundation\Kernel;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class RegistrableFilter
{
    /**
     * The application implementation.
     *
     * @var \Orchestra\Foundation\Kernel
     */
    protected $kernel;

    /**
     * Create a new filter instance.
     *
     * @param  \Orchestra\Foundation\Kernel  $kernel
     */
    public function __construct(Kernel $kernel)
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
