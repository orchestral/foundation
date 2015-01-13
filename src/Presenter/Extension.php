<?php namespace Orchestra\Foundation\Presenter;

use Orchestra\Contracts\Html\Form\Fieldset;
use Orchestra\Contracts\Html\Form\Grid as FormGrid;
use Orchestra\Contracts\Html\Form\Factory as FormFactory;
use Orchestra\Contracts\Extension\Factory as ExtensionContract;

class Extension extends Presenter
{
    /**
     * Implementation of extension contract.
     *
     * @var \Orchestra\Contracts\Extension\Factory
     */
    protected $extension;

    /**
     * Construct a new Extension presenter.
     *
     * @param  \Orchestra\Contracts\Extension\Factory  $extension
     * @param  \Orchestra\Contracts\Html\Form\Factory  $form
     */
    public function __construct(ExtensionContract $extension, FormFactory $form)
    {
        $this->form = $form;
        $this->extension = $extension;
    }

    /**
     * Form View Generator for Orchestra\Extension.
     *
     * @param  \Illuminate\Support\Fluent  $model
     * @param  string  $name
     * @return \Orchestra\Contracts\Html\Form\Builder
     */
    public function configure($model, $name)
    {
        return $this->form->of("orchestra.extension: {$name}", function (FormGrid $form) use ($model, $name) {
            $form->setup($this, "orchestra::extensions/{$name}/configure", $model);

            $handles      = data_get($model, 'handles', $this->extension->option($name, 'handles'));
            $configurable = data_get($model, 'configurable', true);

            $form->fieldset(function (Fieldset $fieldset) use ($handles, $name, $configurable) {
                // We should only cater for custom URL handles for a route.
                if (! is_null($handles) && $configurable !== false) {
                    $fieldset->control('input:text', 'handles')
                        ->label(trans('orchestra/foundation::label.extensions.handles'))
                        ->value($handles);
                }

                $fieldset->control('input:text', 'migrate')
                    ->label(trans('orchestra/foundation::label.extensions.update'))
                    ->field(function () use ($name) {
                        return app('html')->link(
                            handles("orchestra::extensions/{$name}/update", ['csrf' => true]),
                            trans('orchestra/foundation::label.extensions.actions.update'),
                            ['class' => 'btn btn-info']
                        );
                    });
            });
        });
    }
}
