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
        return Form::of("orchestra.extension: {$name}", function ($form) use ($model, $name) {
            $uid = str_replace('/', '.', $name);

            $form->setup($this, "orchestra::extensions/configure/{$uid}", $model);

            $handles      = isset($model->handles) ? $model->handles : E::option($name, 'handles');
            $configurable = isset($model->configurable) ? $model->configurable : true;

            $form->fieldset(function ($fieldset) use ($handles, $name, $configurable) {
                // We should only cater for custom URL handles for a route.
                if (! is_null($handles) && $configurable !== false) {
                    $fieldset->control('input:text', 'handles')
                        ->label(trans('orchestra/foundation::label.extensions.handles'))
                        ->value($handles);
                }

                $fieldset->control('input:text', 'migrate')
                    ->label(trans('orchestra/foundation::label.extensions.update'))
                    ->field(function () use ($name) {
                        $uid = str_replace('/', '.', $name);
                        return HTML::link(
                            handles("orchestra::extensions/update/{$uid}", array('csrf' => true)),
                            trans('orchestra/foundation::label.extensions.actions.update'),
                            array('class' => 'btn btn-info')
                        );
                    });
            });
        });
    }
}
