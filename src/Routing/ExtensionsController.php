<?php namespace Orchestra\Foundation\Routing;

use Illuminate\Support\Fluent;
use Orchestra\Support\Facades\Meta;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Input;
use Orchestra\Support\Facades\Foundation;
use Orchestra\Foundation\Routing\Extension\Controller;
use Orchestra\Foundation\Processor\Extension as ExtensionProcessor;

class ExtensionsController extends Controller
{
    /**
     * Extensions Controller routing to manage available extensions.
     *
     * @param  \Orchestra\Foundation\Processor\Extension  $processor
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
     * Configure an extension.
     *
     * GET (:orchestra)/extensions/configure/(:name)
     *
     * @param  string  $vendor
     * @param  string|null  $package
     * @return mixed
     */
    public function configure($vendor, $package = null)
    {
        $extension = $this->getExtension($vendor, $package);

        return $this->processor->configure($this, $extension);
    }

    /**
     * Update extension configuration.
     *
     * POST (:orchestra)/extensions/configure/(:name)
     *
     * @param  string  $vendor
     * @param  string|null  $package
     * @return mixed
     */
    public function update($vendor, $package = null)
    {
        $extension = $this->getExtension($vendor, $package);

        return $this->processor->update($this, $extension, Input::all());
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
     * Response when update extension configuration failed on validation.
     *
     * @param  mixed   $validation
     * @param  string  $id
     * @return mixed
     */
    public function updateValidationFailed($validation, $id)
    {
        return $this->redirectWithErrors(handles("orchestra::extensions/{$id}/configure"), $validation);
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
