<?php namespace Orchestra\Foundation\Presenter;

use Illuminate\Support\Facades\HTML;
use Orchestra\Support\Facades\Extension as E;
use Orchestra\Support\Facades\Form;

class Extension extends AbstractablePresenter
{
    /**
     * Form View Generator for Orchestra\Extension.
     *
     * @param  \Illuminate\Support\Fluent   $model
     * @param  string                       $name
     * @return \Orchestra\Html\Form\FormBuilder
     */
    public function configure($model, $name)
    {
        $me = $this;

        return Form::of("orchestra.extension: {$name}", function ($form) use ($me, $model, $name) {
            $uid = str_replace('/', '.', $name);

            $form->setup($me, "orchestra::extensions/configure/{$uid}", $model);

            $handles      = isset($model->handles) ? $model->handles : E::option($name, 'handles');
            $configurable = isset($model->configurable) ? $model->configurable : true;

            $form->fieldset(function ($fieldset) use ($handles, $name, $configurable) {
                // We should only cater for custom URL handles for a route.
                if (! is_null($handles) and $configurable !== false) {
                    $fieldset->control('input:text', 'handles', function ($control) use ($handles) {
                        $control->label(trans('orchestra/foundation::label.extensions.handles'));
                        $control->value($handles);
                    });
                }

                $fieldset->control('input:text', 'migrate', function ($control) use ($handles, $name) {
                    $control->label(trans('orchestra/foundation::label.extensions.update'));

                    $control->field(function () use ($name) {
                        $uid = str_replace('/', '.', $name);
                        return HTML::link(
                            handles("orchestra::extensions/update/{$uid}"),
                            trans('orchestra/foundation::label.extensions.actions.update'),
                            array('class' => 'btn btn-info')
                        );
                    });
                });
            });
        });
    }
}
