<?php

namespace Orchestra\Foundation\Http\Controllers\Extension;

use Illuminate\Support\Fluent;
use Orchestra\Support\Facades\Publisher;
use Orchestra\Foundation\Jobs\RefreshRouteCache;
use Orchestra\Extension\Processors\Deactivator as Processor;
use Orchestra\Contracts\Extension\Listener\Deactivator as Listener;

class DeactivateController extends ActionController implements Listener
{
    /**
     * Deactivate an extension.
     *
     * GET (:orchestra)/extensions/activate/(:name)
     *
     * @param  \Orchestra\Extension\Processors\Deactivator  $deactivator
     * @param  string  $vendor
     * @param  string|null  $package
     *
     * @return mixed
     */
    public function __invoke(Processor $deactivator, $vendor, $package = null)
    {
        return $deactivator($this, $this->getExtension($vendor, $package));
    }

    /**
     * Response when extension deactivation has succeed.
     *
     * @param  \Illuminate\Support\Fluent  $extension
     *
     * @return mixed
     */
    public function deactivationHasSucceed(Fluent $extension)
    {
        $this->dispatch(new RefreshRouteCache());

        $message = trans('orchestra/foundation::response.extensions.deactivate', $extension->getAttributes());

        return $this->redirectWithMessage(handles('orchestra::extensions'), $message);
    }
}
