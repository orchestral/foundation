<?php namespace Orchestra\Foundation\Filters;

use Illuminate\Http\RedirectResponse;
use Orchestra\Contracts\Foundation\Foundation;

class CanBeInstalled
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
     */
    public function filter()
    {
        if (! $this->foundation->installed()) {
            return new RedirectResponse($this->foundation->handles('orchestra::install'));
        }
    }
}
