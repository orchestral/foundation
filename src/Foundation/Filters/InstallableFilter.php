<?php namespace Orchestra\Foundation\Filters;

use Orchestra\Foundation\Kernel;
use Illuminate\Http\RedirectResponse;

class InstallableFilter
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
     * @param  \Orchestra\Foundation\Kernel    $kernel
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
        if (! $this->kernel->installed()) {
            return new RedirectResponse(handles('orchestra::install'));
        }
    }
}
