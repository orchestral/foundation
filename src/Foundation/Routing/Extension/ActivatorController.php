<?php namespace Orchestra\Foundation\Routing\Extension;

use Illuminate\Support\Fluent;
use Orchestra\Support\Facades\Publisher;
use Orchestra\Extension\Processor\Activator as Processor;
use Orchestra\Contracts\Extension\Listener\Activator as Listener;

class ActivatorController extends Controller implements Listener
{
    /**
     * Extensions Controller routing to manage extension activation.
     *
     * @param  \Orchestra\Extension\Processor\Activator  $processor
     */
    public function __construct(Processor $processor)
    {
        $this->processor = $processor;

        parent::__construct();
    }

    /**
     * Activate an extension.
     *
     * GET (:orchestra)/extensions/activate/(:name)
     *
     * @param  string  $name
     * @return mixed
     */
    public function activate($name)
    {
        return $this->processor->activate($this, $this->getExtension($name));
    }

    /**
     * Response when extension activation has failed.
     *
     * @param  \Illuminate\Support\Fluent  $extension
     * @param  array  $errors
     * @return mixed
     */
    public function activationHasFailed(Fluent $extension, array $errors)
    {
        Publisher::queue($extension->get('name'));

        return $this->redirect(handles('orchestra::publisher'));
    }

    /**
     * Response when extension activation has succeed.
     *
     * @param  \Illuminate\Support\Fluent  $extension
     * @return mixed
     */
    public function activationHasSucceed(Fluent $extension)
    {
        $message = trans('orchestra/foundation::response.extensions.activate', $extension->getAttributes());

        return $this->redirectWithMessage(handles('orchestra::extensions'), $message);
    }
}
