<?php

namespace Orchestra\Foundation\Http\Controllers\Extension;

use Illuminate\Support\Fluent;
use Orchestra\Support\Facades\Publisher;
use Orchestra\Foundation\Jobs\RefreshRouteCache;
use Orchestra\Extension\Processor\Migrator as MigratorProcessor;
use Orchestra\Extension\Processor\Activator as ActivatorProcessor;
use Orchestra\Extension\Processor\Deactivator as DeactivatorProcessor;
use Orchestra\Contracts\Extension\Listener\Migrator as MigratorListener;
use Orchestra\Contracts\Extension\Listener\Activator as ActivatorListener;
use Orchestra\Contracts\Extension\Listener\Deactivator as DeactivatorListener;

class ActionController extends Controller implements ActivatorListener, DeactivatorListener, MigratorListener
{
    /**
     * Setup controller middleware.
     *
     * @return void
     */
    protected function setupMiddleware()
    {
        $this->middleware('orchestra.auth');
        $this->middleware('orchestra.can:manage-orchestra');
        $this->middleware('orchestra.csrf');
    }

    /**
     * Activate an extension.
     *
     * GET (:orchestra)/extensions/activate/(:name)
     *
     * @param  \Orchestra\Extension\Processor\Activator  $activator
     * @param  string  $vendor
     * @param  string|null  $package
     *
     * @return mixed
     */
    public function activate(ActivatorProcessor $activator, $vendor, $package = null)
    {
        return $activator->activate($this, $this->getExtension($vendor, $package));
    }

    /**
     * Update an extension, run migration and asset publish command.
     *
     * GET (:orchestra)/extensions/activate/(:name)
     *
     * @param  \Orchestra\Extension\Processor\Migrator  $migrator
     * @param  string  $vendor
     * @param  string|null  $package
     *
     * @return mixed
     */
    public function migrate(MigratorProcessor $migrator, $vendor, $package = null)
    {
        return $migrator->migrate($this, $this->getExtension($vendor, $package));
    }

    /**
     * Deactivate an extension.
     *
     * GET (:orchestra)/extensions/deactivate/(:name)
     *
     * @param  \Orchestra\Extension\Processor\Deactivator  $deactivator
     * @param  string  $vendor
     * @param  string|null  $package
     *
     * @return mixed
     */
    public function deactivate(DeactivatorProcessor $deactivator, $vendor, $package = null)
    {
        return $deactivator->deactivate($this, $this->getExtension($vendor, $package));
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

        $message = trans('orchestra/foundation::response.extensions.activate', $extension->getAttributes());

        return $this->redirectWithMessage(handles('orchestra::extensions'), $message);
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

    /**
     * Queue publishing asset to publisher.
     *
     * @param  \Illuminate\Support\Fluent  $extension
     *
     * @return mixed
     */
    protected function queueToPublisher(Fluent $extension)
    {
        Publisher::queue($extension->get('name'));

        return $this->redirect(handles('orchestra::publisher'));
    }
}
