<?php

namespace Orchestra\Foundation\Processors\Extension;

use Illuminate\Support\Fluent;
use Illuminate\Support\Facades\Event;
use Orchestra\Support\Facades\Extension;
use Orchestra\Support\Facades\Foundation;
use Orchestra\Foundation\Processors\Processor;
use Orchestra\Foundation\Validations\Extension as Validator;
use Orchestra\Contracts\Extension\Command\Configure as Command;
use Orchestra\Foundation\Http\Presenters\Extension as Presenter;
use Orchestra\Contracts\Extension\Listener\Configure as Listener;

class Configure extends Processor implements Command
{
    /**
     * Create a new processor instance.
     *
     * @param  \Orchestra\Foundation\Http\Presenters\Extension  $presenter
     * @param  \Orchestra\Foundation\Validations\Extension  $validator
     */
    public function __construct(Presenter $presenter, Validator $validator)
    {
        $this->presenter = $presenter;
        $this->validator = $validator;
    }

    /**
     * View edit extension configuration page.
     *
     * @param  \Orchestra\Contracts\Extension\Listener\Configure  $listener
     * @param  \Illuminate\Support\Fluent  $extension
     *
     * @return mixed
     */
    public function configure(Listener $listener, Fluent $extension)
    {
        if (! Extension::started($extension->get('name'))) {
            return $listener->abortWhenRequirementMismatched();
        }

        $memory = Foundation::memory();

        // Load configuration from memory.
        $activeConfig = (array) $memory->get("extensions.active.{$extension->get('name')}.config", []);
        $baseConfig = (array) $memory->get("extension_{$extension->get('name')}", []);

        $eloquent = new Fluent(\array_merge($activeConfig, $baseConfig));

        // Add basic form, allow extension to add custom configuration field
        // to this form using events.
        $form = $this->presenter->configure($eloquent, $extension->get('name'));

        Event::dispatch("orchestra.form: extension.{$extension->get('name')}", [$eloquent, $form]);

        return $listener->showConfigurationChanger(\compact('eloquent', 'form', 'extension'));
    }

    /**
     * Update an extension configuration.
     *
     * @param  \Orchestra\Contracts\Extension\Listener\Configure  $listener
     * @param  \Illuminate\Support\Fluent  $extension
     * @param  array  $input
     *
     * @return mixed
     */
    public function update(Listener $listener, Fluent $extension, array $input)
    {
        if (! Extension::started($extension->get('name'))) {
            return $listener->suspend(404);
        }

        $validation = $this->validator->with($input, ["orchestra.validate: extension.{$extension->get('name')}"]);

        if ($validation->fails()) {
            return $listener->updateConfigurationFailedValidation($validation->getMessageBag(), $extension->uid);
        }

        $input['handles'] = \str_replace(['{domain}', '{{domain}}'], '{{domain}}', $input['handles']);
        unset($input['_token']);

        $memory = Foundation::memory();
        $config = (array) $memory->get("extension.active.{$extension->get('name')}.config", []);
        $input = new Fluent(\array_merge($config, $input));

        Event::dispatch("orchestra.saving: extension.{$extension->get('name')}", [&$input]);

        $memory->put("extensions.active.{$extension->get('name')}.config", $input->getAttributes());
        $memory->put("extension_{$extension->get('name')}", $input->getAttributes());

        Event::dispatch("orchestra.saved: extension.{$extension->get('name')}", [$input]);

        return $listener->configurationUpdated($extension);
    }
}
