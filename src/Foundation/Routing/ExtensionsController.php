<?php namespace Orchestra\Foundation\Routing;

use Illuminate\Support\Fluent;
use Orchestra\Support\Facades\Meta;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Input;
use Orchestra\Support\Facades\Foundation;
use Orchestra\Foundation\Processor\Extension as ExtensionProcessor;

class ExtensionsController extends AdminController
{
    /**
     * Extensions Controller routing to manage available extensions.
     *
     * @param  \Orchestra\Foundation\Processor\Extension   $processor
     */
    public function __construct(ExtensionProcessor $processor)
    {
        $this->processor = $processor;

        parent::__construct();
    }

    /**
     * Setup controller filters.
     *
     * @return void
     */
    protected function setupFilters()
    {
        $this->beforeFilter('orchestra.auth');
        $this->beforeFilter('orchestra.manage');
    }

    /**
     * List all available extensions.
     *
     * GET (:orchestra)/extensions
     *
     * @return mixed
     */
    public function index()
    {
        return $this->processor->index($this);
    }

    /**
     * Activate an extension.
     *
     * GET (:orchestra)/extensions/activate/(:name)
     *
     * @param  string   $uid
     * @return mixed
     */
    public function activate($uid)
    {
        $extension = $this->getExtension($uid);

        return $this->processor->activate($this, $extension);
    }

    /**
     * Deactivate an extension.
     *
     * GET (:orchestra)/extensions/deactivate/(:name)
     *
     * @param  string   $uid
     * @return mixed
     */
    public function deactivate($uid)
    {
        $extension = $this->getExtension($uid);

        return $this->processor->deactivate($this, $extension);
    }

    /**
     * Update an extension, run migration and asset publish command.
     *
     * GET (:orchestra)/extensions/update/(:name)
     *
     * @param  string   $uid
     * @return mixed
     */
    public function migrate($uid)
    {
        $extension = $this->getExtension($uid);

        return $this->processor->migrate($this, $extension);
    }

    /**
     * Configure an extension.
     *
     * GET (:orchestra)/extensions/configure/(:name)
     *
     * @param  string   $uid
     * @return mixed
     */
    public function configure($uid)
    {
        $extension = $this->getExtension($uid);

        return $this->processor->configure($this, $extension);
    }

    /**
     * Update extension configuration.
     *
     * POST (:orchestra)/extensions/configure/(:name)
     *
     * @param  string   $uid
     * @return mixed
     */
    public function update($uid)
    {
        $extension = $this->getExtension($uid);

        return $this->processor->update($this, $extension, Input::all());
    }

    /**
     * Get extension information.
     *
     * @param  string  $uid
     * @return \Illuminate\Support\Fluent
     */
    protected function getExtension($uid)
    {
        $name = str_replace('.', '/', $uid);

        return new Fluent(array('name' => $name, 'uid' => $uid));
    }

    /**
     * Response for list of extensions page.
     *
     * @param  array  $data
     * @return mixed
     */
    public function indexSucceed(array $data)
    {
        Meta::set('title', trans("orchestra/foundation::title.extensions.list"));

        return View::make('orchestra/foundation::extensions.index', $data);
    }

    /**
     * Response for extension configuration page.
     *
     * @param  array  $data
     * @return mixed
     */
    public function configureSucceed(array $data)
    {
        $name = $data['extension']->name;

        Meta::set('title', Foundation::memory()->get("extensions.available.{$name}.name", $name));
        Meta::set('description', trans("orchestra/foundation::title.extensions.configure"));

        return View::make('orchestra/foundation::extensions.configure', $data);
    }

    /**
     * Response when activate an extension failed.
     *
     * @param  \Illuminate\Support\Fluent  $extension
     * @return mixed
     */
    public function activateFailed(Fluent $extension)
    {
        return $this->redirect(handles('orchestra::publisher'));
    }

    /**
     * Response when activate an extension succeed.
     *
     * @param  \Illuminate\Support\Fluent  $extension
     * @return mixed
     */
    public function activateSucceed(Fluent $extension)
    {
        $message = trans('orchestra/foundation::response.extensions.activate', $extension->getAttributes());

        return $this->redirectWithMessage(handles('orchestra::extensions'), $message);
    }

    /**
     * Response when migrate an extension failed.
     *
     * @param  \Illuminate\Support\Fluent  $extension
     * @return mixed
     */
    public function migrateFailed(Fluent $extension)
    {
        return $this->redirect(handles('orchestra::publisher'));
    }

    /**
     * Response when migrate an extension succeed.
     *
     * @param  \Illuminate\Support\Fluent  $extension
     * @return mixed
     */
    public function migrateSucceed(Fluent $extension)
    {
        $message = trans('orchestra/foundation::response.extensions.migrate', $extension->getAttributes());

        return $this->redirectWithMessage(handles('orchestra::extensions'), $message);
    }

    /**
     * Response when deactivate an extension succeed.
     *
     * @param  \Illuminate\Support\Fluent  $extension
     * @return mixed
     */
    public function deactivateSucceed(Fluent $extension)
    {
        $message = trans('orchestra/foundation::response.extensions.deactivate', $extension->getAttributes());

        return $this->redirectWithMessage(handles('orchestra::extensions'), $message);
    }

    /**
     * Response when update extension configuration failed on validation.
     *
     * @param  mixed   $validation
     * @param  string  $id
     * @return mixed
     */
    public function updateValidationFailed($validation, $id)
    {
        return $this->redirectWithErrors(handles("orchestra::extensions/configure/{$id}"), $validation);
    }

    /**
     * Response when update extension configuration succeed.
     *
     * @param  \Illuminate\Support\Fluent  $extension
     * @return mixed
     */
    public function updateSucceed(Fluent $extension)
    {
        $message = trans("orchestra/foundation::response.extensions.configure", $extension->getAttributes());

        return $this->redirectWithMessage(handles('orchestra::extensions'), $message);
    }
}
