<?php namespace Orchestra\Foundation\Processor;

use Closure;
use Illuminate\Support\Fluent;
use Illuminate\Support\Facades\Event;
use Orchestra\Support\Facades\Publisher;
use Orchestra\Support\Facades\Foundation;
use Orchestra\Support\Facades\Extension as E;
use Orchestra\Contracts\Publisher\FilePermissionException;
use Orchestra\Foundation\Presenter\Extension as ExtensionPresenter;
use Orchestra\Foundation\Validation\Extension as ExtensionValidator;

class Extension extends Processor
{
    /**
     * Create a new processor instance.
     *
     * @param  \Orchestra\Foundation\Presenter\Extension  $presenter
     * @param  \Orchestra\Foundation\Validation\Extension  $validator
     */
    public function __construct(ExtensionPresenter $presenter, ExtensionValidator $validator)
    {
        $this->presenter = $presenter;
        $this->validator = $validator;
    }

    /**
     * View all extension page.
     *
     * @param  object  $listener
     * @return mixed
     */
    public function index($listener)
    {
        $data['extensions'] = E::detect();

        return $listener->indexSucceed($data);
    }

    /**
     * View edit extension configuration page.
     *
     * @param  object  $listener
     * @param  \Illuminate\Support\Fluent  $extension
     * @return mixed
     */
    public function configure($listener, Fluent $extension)
    {
        if (! E::started($extension->get('name'))) {
            return $listener->suspend(404);
        }

        $memory = Foundation::memory();

        // Load configuration from memory.
        $activeConfig = (array) $memory->get("extensions.active.{$extension->get('name')}.config", []);
        $baseConfig   = (array) $memory->get("extension_{$extension->get('name')}", []);

        $eloquent = new Fluent(array_merge($activeConfig, $baseConfig));

        // Add basic form, allow extension to add custom configuration field
        // to this form using events.
        $form = $this->presenter->configure($eloquent, $extension->get('name'));

        Event::fire("orchestra.form: extension.{$extension->get('name')}", [$eloquent, $form]);

        return $listener->configureSucceed(compact('eloquent', 'form', 'extension'));
    }

    /**
     * Update an extension configuration.
     *
     * @param  object  $listener
     * @param  \Illuminate\Support\Fluent  $extension
     * @param  array  $input
     * @return mixed
     */
    public function update($listener, Fluent $extension, array $input)
    {
        if (! E::started($extension->get('name'))) {
            return $listener->suspend(404);
        }

        $validation = $this->validator->with($input, ["orchestra.validate: extension.{$extension->get('name')}"]);

        if ($validation->fails()) {
            return $listener->updateValidationFailed($validation, $extension->uid);
        }

        $memory = Foundation::memory();
        $config = (array) $memory->get("extension.active.{$extension->get('name')}.config", []);
        $input  = new Fluent(array_merge($config, $input));

        unset($input['_token']);

        Event::fire("orchestra.saving: extension.{$extension->get('name')}", [& $input]);

        $memory->put("extensions.active.{$extension->get('name')}.config", $input->getAttributes());
        $memory->put("extension_{$extension->get('name')}", $input->getAttributes());

        Event::fire("orchestra.saved: extension.{$extension->get('name')}", [$input]);

        return $listener->updateSucceed($extension);
    }
}
