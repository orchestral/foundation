<?php namespace Orchestra\Foundation\Processor;

use Closure;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Fluent;
use Orchestra\Extension\FilePermissionException;
use Orchestra\Foundation\Routing\BaseController;
use Orchestra\Foundation\Presenter\Extension as ExtensionPresenter;
use Orchestra\Foundation\Validation\Extension as ExtensionValidator;
use Orchestra\Support\Facades\App;
use Orchestra\Support\Facades\Extension as E;
use Orchestra\Support\Facades\Publisher;

class Extension extends AbstractableProcessor
{
    /**
     * Create a new processor instance.
     *
     * @param  \Orchestra\Foundation\Presenter\Extension    $presenter
     * @param  \Orchestra\Foundation\Validation\Extension   $validator
     */
    public function __construct(ExtensionPresenter $presenter, ExtensionValidator $validator)
    {
        $this->presenter = $presenter;
        $this->validator = $validator;
    }

    /**
     * View all extension page.
     *
     * @param  \Orchestra\Foundation\Routing\BaseController    $listener
     * @return mixed
     */
    public function index(BaseController $listener)
    {
        $data['extensions'] = E::detect();

        return $listener->indexSucceed($data);
    }

    /**
     * Activate an extension.
     *
     * @param  \Orchestra\Foundation\Routing\BaseController    $listener
     * @param  \Illuminate\Support\Fluent                      $extension
     * @return mixed
     */
    public function activate(BaseController $listener, Fluent $extension)
    {
        if (E::started($extension->name)) {
            return $listener->suspend(404);
        }

        $type = 'activate';

        return $this->execute($listener, $type, $extension->name, function ($name) {
            E::activate($name);
        });
    }

    /**
     * Deactivate an extension.
     *
     * @param  \Orchestra\Foundation\Routing\BaseController    $listener
     * @param  \Illuminate\Support\Fluent                      $extension
     * @return mixed
     */
    public function deactivate(BaseController $listener, Fluent $extension)
    {
        if (! E::started($extension->name) and ! E::activated($extension->name)) {
            return $listener->suspend(404);
        }

        E::deactivate($extension->name);

        return $listener->executionSucceed(
            trans('orchestra/foundation::response.extensions.deactivate', compact('name'))
        );
    }

    /**
     * Update/migrate an extension.
     *
     * @param  \Orchestra\Foundation\Routing\BaseController    $listener
     * @param  \Illuminate\Support\Fluent                      $extension
     * @return mixed
     */
    public function migrate(BaseController $listener, Fluent $extension)
    {
        if (! E::started($extension->name)) {
            return $listener->suspend(404);
        }

        $type = 'update';

        return $this->execute($listener, $type, $extension->name, function ($name) {
            E::publish($name);
        });
    }

    /**
     * View edit extension configuration page.
     *
     * @param  \Orchestra\Foundation\Routing\BaseController    $listener
     * @param  \Illuminate\Support\Fluent                      $extension
     * @return mixed
     */
    public function configure(BaseController $listener, Fluent $extension)
    {
        if (! E::started($extension->name)) {
            return $listener->suspend(404);
        }

        $memory = App::memory();

        // Load configuration from memory.
        $activeConfig = (array) $memory->get("extensions.active.{$extension->name}.config", array());
        $baseConfig   = (array) $memory->get("extension_{$extension->name}", array());

        $eloquent = new Fluent(array_merge($activeConfig, $baseConfig));

        // Add basic form, allow extension to add custom configuration field
        // to this form using events.
        $form = $this->presenter->configure($eloquent, $extension->name);

        Event::fire("orchestra.form: extension.{$extension->name}", array($eloquent, $form));

        return $listener->configureSucceed(compact('eloquent', 'form'));
    }

    /**
     * Update an extension configuration.
     *
     * @param  \Orchestra\Foundation\Routing\BaseController    $listener
     * @param  \Illuminate\Support\Fluent                      $extension
     * @param  array                                           $input
     * @return mixed
     */
    public function update(BaseController $listener, Fluent $extension, array $input)
    {
        if (! E::started($extension->name)) {
            return $listener->suspend(404);
        }

        $validation = $this->validator->with($input, array("orchestra.validate: extension.{$extension->name}"));

        if ($validation->fails()) {
            return $listener->updateValidationFailed($validation, $extension->uid);
        }

        $memory = App::memory();
        $config = (array) $memory->get("extension.active.{$extension->name}.config", array());
        $input  = new Fluent(array_merge($config, $input));

        unset($input['_token']);

        Event::fire("orchestra.saving: extension.{$extension->name}", array( & $input));

        $memory->put("extensions.active.{$extension->name}.config", $input->getAttributes());
        $memory->put("extension_{$extension->name}", $input->getAttributes());

        Event::fire("orchestra.saved: extension.{$extension->name}", array($input));

        return $listener->updateSucceed(
            trans("orchestra/foundation::response.extensions.configure", compact('name')),
            $extension
        );
    }

    /**
     * Execute installation or update for an extension.
     *
     * @param  \Orchestra\Foundation\Routing\BaseController    $listener
     * @param  string                                          $type
     * @param  string                                          $name
     * @param  Closure                                         $callback
     * @return mixed
     */
    protected function execute(BaseController $listener, $type, $name, Closure $callback)
    {
        try {
            // Check if folder is writable via the web instance, this would
            // avoid issue running Orchestra Platform with debug as true where
            // creating/copying the directory would throw an ErrorException.
            if (! E::permission($name)) {
                throw new FilePermissionException("[{$name}] is not writable.");
            }

            call_user_func($callback, $name);
        } catch (FilePermissionException $e) {
            // In events where extension can't be activated due to extension
            // publish failed to push the asset to proper path. We need to
            // put this under a publisher queue.
            Publisher::queue($name);

            return $listener->executionFailed();
        }

        return $listener->executionSucceed(
            trans("orchestra/foundation::response.extensions.{$type}", compact('name'))
        );
    }
}
