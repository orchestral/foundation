<?php

namespace Orchestra\Foundation\Http\Controllers\Extension;

use Illuminate\Http\Request;
use Illuminate\Support\Fluent;
use Orchestra\Contracts\Extension\Listener\Configure as Listener;
use Orchestra\Foundation\Jobs\RefreshRouteCache;
use Orchestra\Foundation\Processors\Extension\Configure as Processor;

class ConfigureController extends Controller implements Listener
{
    /**
     * Setup controller middleware.
     *
     * @return void
     */
    protected function onCreate()
    {
        $this->middleware([
            'orchestra.auth',
            'orchestra.can:manage-orchestra',
        ]);
    }

    /**
     * Configure an extension.
     *
     * GET (:orchestra)/extensions/configure/(:name)
     *
     * @param  \Orchestra\Foundation\Processors\Extension\Configure  $processor
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
     * @param  \Illuminate\Http\Request  $request
     * @param  \Orchestra\Foundation\Processors\Extension\Configure  $processor
     * @param  string  $vendor
     * @param  string|null  $package
     *
     * @return mixed
     */
    public function update(Request $request, Processor $processor, $vendor, $package = null)
    {
        $extension = $this->getExtension($vendor, $package);

        return $processor->update($this, $extension, $request->all());
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

        \set_meta('title', \memorize("extensions.available.{$name}.name", $name));
        \set_meta('description', \trans('orchestra/foundation::title.extensions.configure'));

        return \view('orchestra/foundation::extensions.configure', $data);
    }

    /**
     * Response when update extension configuration failed on validation.
     *
     * @param  \Illuminate\Contracts\Support\MessageBag|array  $errors
     * @param  string  $id
     *
     * @return mixed
     */
    public function updateConfigurationFailedValidation($errors, $id)
    {
        return $this->redirectWithErrors(\handles("orchestra::extensions/{$id}/configure"), $errors);
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

        $message = \trans('orchestra/foundation::response.extensions.configure', $extension->getAttributes());

        return $this->redirectWithMessage(\handles('orchestra::extensions'), $message);
    }
}
