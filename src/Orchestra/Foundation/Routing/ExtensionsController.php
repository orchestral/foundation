<?php namespace Orchestra\Foundation\Routing;

use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Fluent;
use Orchestra\Support\Facades\Messages;
use Orchestra\Support\Facades\Site;
use Orchestra\Foundation\Processor\Extension as ExtensionProcessor;


use Orchestra\Support\Facades\App;
use Orchestra\Support\Facades\Extension;

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
     * @return Response
     */
    public function index()
    {
        Site::set('title', trans("orchestra/foundation::title.extensions.list"));

        return $this->processor->index($this);
    }

    /**
     * Activate an extension.
     *
     * GET (:orchestra)/extensions/activate/(:name)
     *
     * @param  string   $uid
     * @return Response
     */
    public function activate($uid)
    {
        $name = str_replace('.', '/', $uid);
        $extension = new Fluent(compact('name', 'uid'));

        return $this->processor->activate($this, $extension);
    }

    /**
     * Deactivate an extension.
     *
     * GET (:orchestra)/extensions/deactivate/(:name)
     *
     * @param  string   $uid
     * @return Response
     */
    public function deactivate($uid)
    {
        $name = str_replace('.', '/', $uid);
        $extension = new Fluent(compact('name', 'uid'));

        return $this->processor->deactivate($this, $extension);
    }

    /**
     * Update an extension, run migration and asset publish command.
     *
     * GET (:orchestra)/extensions/update/(:name)
     *
     * @param  string   $uid
     * @return Response
     */
    public function migrate($uid)
    {
        $name = str_replace('.', '/', $uid);
        $extension = new Fluent(compact('name', 'uid'));

        return $this->processor->migrate($this, $extension);
    }

    /**
     * Configure an extension.
     *
     * GET (:orchestra)/extensions/configure/(:name)
     *
     * @param  string   $uid
     * @return Response
     */
    public function configure($uid)
    {
        $name = str_replace('.', '/', $uid);
        $extension = new Fluent(compact('name', 'uid'));

        Site::set('title', App::memory()->get("extensions.available.{$name}.name", $name));
        Site::set('description', trans("orchestra/foundation::title.extensions.configure"));

        return $this->processor->configure($this, $extension);
    }

    /**
     * Update extension configuration.
     *
     * POST (:orchestra)/extensions/configure/(:name)
     *
     * @param  string   $uid
     * @return Response
     */
    public function update($uid)
    {
        $name = str_replace('.', '/', $uid);
        $extension = new Fluent(compact('name', 'uid'));

        return $this->processor->update($this, $extension, Input::all());
    }

    /**
     * Response for list of extensions page.
     *
     * @param  array  $data
     * @return Response
     */
    public function indexSucceed(array $data)
    {
        return View::make('orchestra/foundation::extensions.index', $data);
    }

    /**
     * Response for extension configuration page.
     *
     * @param  array  $data
     * @return Response
     */
    public function configureSucceed(array $data)
    {
        return View::make('orchestra/foundation::extensions.configure', $data);
    }

    /**
     * Response when action to extension failed.
     *
     * @param  string|null $message
     * @return Response
     */
    public function executionFailed($message = null)
    {
        return $this->redirectWithMessage(handles('orchestra::publisher'), $message);
    }

    /**
     * Response when action to extension succeed.
     *
     * @param  string|null $message
     * @return Response
     */
    public function executionSucceed($message = null)
    {
        return $this->redirectWithMessage(handles('orchestra::extensions'), $message);
    }

    /**
     * Response when update extension configuration failed on validation.
     *
     * @param  mixed   $validation
     * @param  string  $id
     * @return Response
     */
    public function updateValidationFailed($validation, $id)
    {
        return $this->redirectWithErrors(handles("orchestra::extensions/configure/{$id}"), $validation);
    }

    /**
     * Response when update extension configuration succeed.
     *
     * @param  string|null $message
     * @return Response
     */
    public function updateSucceed($message = null)
    {
        return $this->redirectWithMessage(handles('orchestra::extensions'), $message);
    }
}
