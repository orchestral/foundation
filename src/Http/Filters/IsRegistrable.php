<?php namespace Orchestra\Foundation\Http\Filters;

use Orchestra\Contracts\Foundation\Foundation;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class IsRegistrable
{
    /**
     * The application implementation.
     *
     * @var \Orchestra\Contracts\Foundation\Foundation
     */
    protected $foundation;

    /**
     * Create a new filter instance.
     *
     * @param  \Orchestra\Contracts\Foundation\Foundation  $foundation
     */
    public function __construct(Foundation $foundation)
    {
        $this->foundation = $foundation;
    }

    /**
     * Run the request filter.
     *
     * @return mixed
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     */
    public function filter()
    {
        if (! $this->foundation->memory()->get('site.registrable', false)) {
            throw new NotFoundHttpException('User registration is not allowed.');
        }
    }
}
