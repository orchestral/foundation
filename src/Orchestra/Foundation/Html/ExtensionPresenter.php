<?php namespace Orchestra\Foundation\Html;

use Illuminate\Support\Facades\HTML;
use Orchestra\Support\Facades\Extension;
use Orchestra\Support\Facades\Form;

class ExtensionPresenter {

	/**
	 * Form View Generator for Orchestra\Extension.
	 *
	 * @param  \Illuminate\Support\Fluent   $model
	 * @param  string                       $name
	 * @return \Orchestra\Html\Form\FormBuilder
	 */
	public function form($model, $name)
	{
		return Form::of("orchestra.extension: {$name}", function ($form) use ($model, $name)
		{
			$uid = str_replace('/', '.', $name);
			
			$form->with($model);
			$form->layout('orchestra/foundation::components.form');
			$form->attributes(array(
				'url'    => handles("orchestra::extensions/configure/{$uid}"),
				'method' => "POST",
			));

			$handles      = isset($model->handles) ? $model->handles : Extension::option($name, 'handles');
			$configurable = isset($model->configurable) ? $model->configurable : true;

			$form->fieldset(function ($fieldset) use ($handles, $name, $configurable)
			{
				// We should only cater for custom URL handles for a route.
				if ( ! is_null($handles) and $configurable !== false)
				{
					$fieldset->control('input:text', 'handles', function ($control) use ($handles)
					{
						$control->label(trans('orchestra/foundation::label.extensions.handles'));
						$control->value($handles);
					});
				}

				$fieldset->control('input:text', 'migrate', function ($control) use ($handles, $name)
				{
					$control->label(trans('orchestra/foundation::label.extensions.update'));

					$control->field(function() use ($name)
					{
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
