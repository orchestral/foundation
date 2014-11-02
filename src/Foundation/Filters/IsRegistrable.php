<?php namespace Orchestra\Foundation\Filters;

use Orchestra\Foundation\Foundation;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class IsRegistrable
{
    /**
     * The application implementation.
     *
     * @var \Orchestra\Foundation\Foundation
     */
    protected $foundation;

    /**
     * Create a new filter instance.
     *
     * @param  \Orchestra\Foundation\Foundation  $foundation
     */
    public function __construct(Foundation $foundation)
    {
        $this->foundation = $foundation;
    }

    /**
     * Run the request filter.
     *
     * @return mixed
     */
    public function filter()
    {
        if (! $this->foundation->memory()->get('site.registrable', false)) {
            throw new NotFoundHttpException('User registration is not available.');
        }
    }
}
