<?php

namespace Orchestra\Foundation\Http\Controllers\Extension;

use Illuminate\Support\Fluent;
use Orchestra\Foundation\Jobs\RefreshRouteCache;
use Orchestra\Extension\Processors\Activator as Processor;
use Orchestra\Contracts\Extension\Listener\Activator as Listener;

class ActivateController extends ActionController implements Listener
{
    /**
     * Activate an extension.
     *
     * GET (:orchestra)/extensions/activate/(:name)
     *
     * @param  \Orchestra\Extension\Processors\Activator  $activator
     * @param  string  $vendor
     * @param  string|null  $package
     *
     * @return mixed
     */
    public function __invoke(Processor $activator, $vendor, $package = null)
    {
        return $activator($this, $this->getExtension($vendor, $package));
    }

    /**
     * Response when extension activation has failed.
     *
     * @param  \Illuminate\Support\Fluent  $extension
     * @param  array  $errors
     *
     * @return mixed
     */
    public function activationHasFailed(Fluent $extension, array $errors)
    {
        return $this->queueToPublisher($extension);
    }

    /**
     * Response when extension activation has succeed.
     *
     * @param  \Illuminate\Support\Fluent  $extension
     *
     * @return mixed
     */
    public function activationHasSucceed(Fluent $extension)
    {
        $this->dispatch(new RefreshRouteCache());

        $message = \trans('orchestra/foundation::response.extensions.activate', $extension->getAttributes());

        return $this->redirectWithMessage(\handles('orchestra::extensions'), $message);
    }
}
