<?php

namespace Orchestra\Foundation\Http\Controllers\Extension;

use Illuminate\Support\Fluent;
use Orchestra\Support\Facades\Publisher;
use Orchestra\Foundation\Jobs\RefreshRouteCache;
use Orchestra\Extension\Processors\Migrator as Processor;
use Orchestra\Contracts\Extension\Listener\Migrator as Listener;

class MigrateController extends ActionController implements Listener
{
    /**
     * Migrate an extension.
     *
     * GET (:orchestra)/extensions/activate/(:name)
     *
     * @param  \Orchestra\Extension\Processors\Migrator  $migrator
     * @param  string  $vendor
     * @param  string|null  $package
     *
     * @return mixed
     */
    public function __invoke(Processor $migrator, $vendor, $package = null)
    {
        return $migrator($this, $this->getExtension($vendor, $package));
    }

    /**
     * Response when extension migration has failed.
     *
     * @param  \Illuminate\Support\Fluent $extension
     * @param  array $errors
     *
     * @return mixed
     */
    public function migrationHasFailed(Fluent $extension, array $errors)
    {
        return $this->queueToPublisher($extension);
    }

    /**
     * Response when extension migration has succeed.
     *
     * @param  \Illuminate\Support\Fluent $extension
     *
     * @return mixed
     */
    public function migrationHasSucceed(Fluent $extension)
    {
        $message = trans('orchestra/foundation::response.extensions.migrate', $extension->getAttributes());

        return $this->redirectWithMessage(handles('orchestra::extensions'), $message);
    }
}
