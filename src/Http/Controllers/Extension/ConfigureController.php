<?php

namespace Orchestra\Foundation\Http\Controllers\Extension;

use Illuminate\Support\Fluent;
use Illuminate\Support\Facades\Input;
use Orchestra\Support\Facades\Foundation;
use Orchestra\Foundation\Jobs\RefreshRouteCache;
use Orchestra\Contracts\Extension\Listener\Configure as Listener;
use Orchestra\Foundation\Processor\Extension\Configure as Processor;

class ConfigureController extends Controller implements Listener
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
    }

    /**
     * Configure an extension.
     *
     * GET (:orchestra)/extensions/configure/(:name)
     *
     * @param  \Orchestra\Foundation\Processor\Extension\Configure  $processor
     * @param  string  $vendor
     * @param  string|null  $package
     *
     * @return mixed
     */
    public function configure(Processor $processor, $vendor, $package = null)
    {
        $extension = $this->getExtension($vendor, $package);

        return $processor->configure($this, $extension);
    }

    /**
     * Update extension configuration.
     *
     * POST (:orchestra)/extensions/configure/(:name)
     *
     * @param  \Orchestra\Foundation\Processor\Extension\Configure  $processor
     * @param  string  $vendor
     * @param  string|null  $package
     *
     * @return mixed
     */
    public function update(Processor $processor, $vendor, $package = null)
    {
        $extension = $this->getExtension($vendor, $package);

        return $processor->update($this, $extension, Input::all());
    }

    /**
     * Response for extension configuration.
     *
     * @param  array  $data
     *
     * @return mixed
     */
    public function showConfigurationChanger(array $data)
    {
        $name = $data['extension']->name;

        set_meta('title', Foundation::memory()->get("extensions.available.{$name}.name", $name));
        set_meta('description', trans('orchestra/foundation::title.extensions.configure'));

        return view('orchestra/foundation::extensions.configure', $data);
    }

    /**
     * Response when update extension configuration failed on validation.
     *
     * @param  \Illuminate\Support\MessageBag|array  $errors
     * @param  string  $id
     *
     * @return mixed
     */
    public function updateConfigurationFailedValidation($errors, $id)
    {
        return $this->redirectWithErrors(handles("orchestra::extensions/{$id}/configure"), $errors);
    }

    /**
     * Response when update extension configuration succeed.
     *
     * @param  \Illuminate\Support\Fluent  $extension
     *
     * @return mixed
     */
    public function configurationUpdated(Fluent $extension)
    {
        $this->dispatch(new RefreshRouteCache());

        $message = trans('orchestra/foundation::response.extensions.configure', $extension->getAttributes());

        return $this->redirectWithMessage(handles('orchestra::extensions'), $message);
    }
}
