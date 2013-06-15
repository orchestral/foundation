<?php namespace Orchestra\Services\Html;

use Illuminate\Support\Facades\HTML;
use Illuminate\Support\Facades\Form as F;
use Orchestra\Support\Facades\Form;
use Orchestra\Support\Facades\Table;

class SettingPresenter {

	/**
	 * Form View Generator for Setting Page.
	 *
	 * @static
	 * @access public
	 * @param  \Illuminate\Support\Fluent   $model
	 * @return \Orchestra\Html\Form\FormBuilder
	 */
	public static function form($model)
	{
		return Form::of('orchestra.settings', function ($form) use ($model)
		{
			$form->with($model);
			$form->attributes(array(
				'url'    => handles('orchestra/foundation::settings'),
				'method' => 'POST',
			));

			$form->fieldset(trans('orchestra/foundation::label.settings.application'), function ($fieldset)
			{
				$fieldset->control('input:text', 'site_name', function ($control)
				{
					$control->label(trans('orchestra/foundation::label.name'));
				});

				$fieldset->control('textarea', 'site_description', function ($control)
				{
					$control->label(trans('orchestra/foundation::label.description'));
					$control->attributes(array('rows' => 3));
				});

				$fieldset->control('select', 'site_registrable', function ($control)
				{
					$control->label(trans('orchestra/foundation::label.settings.user-registration'));
					$control->attributes(array('role' => 'switcher'));
					$control->options(array(
						'yes' => 'Yes',
						'no'  => 'No',
					));
				});
			});

			$form->fieldset(trans('orchestra/foundation::label.settings.mail'), function ($fieldset) use ($model)
			{
				$fieldset->control('select', 'email_driver', function ($control)
				{
					$control->label(trans('orchestra/foundation::label.email.driver'));
					$control->options(array(
						'mail'     => 'Mail',
						'smtp'     => 'SMTP',
						'sendmail' => 'Sendmail'
					));
				});

				$fieldset->control('input:text', 'email_host', function ($control)
				{
					$control->label(trans('orchestra/foundation::label.email.host'));
				});

				$fieldset->control('input:text', 'email_port', function ($control)
				{
					$control->label(trans('orchestra/foundation::label.email.port'));
				});
				
				$fieldset->control('input:text', 'email_address', function ($control)
				{
					$control->label(trans('orchestra/foundation::label.email.from'));
				});

				$fieldset->control('input:text', 'email_username', function ($control)
				{
					$control->label(trans('orchestra/foundation::label.email.username'));
				});

				$fieldset->control('input:password', 'email_password', function ($control) use ($model)
				{
					$help = array(
						HTML::create('span', str_repeat('*', strlen($model->email_smtp_password))),
						'&nbsp;&nbsp;',
						HTML::link('#', trans('orchestra/foundation::label.email.change_password'), array(
							'id' => 'change_password_button',
							'class' => 'btn btn-mini btn-warning',
						)),
						F::hidden('change_password', 'no'),
					);

					$control->label(trans('orchestra/foundation::label.email.password'));
					$control->help(HTML::create('span', HTML::raw(implode('', $help)), array(
						'id' => 'change_password_container',
					)));
				});
				
				$fieldset->control('input:text', 'email_encryption', function ($control)
				{
					$control->label(trans('orchestra/foundation::label.email.encryption'));
				});

				$fieldset->control('input:text', 'email_sendmail', function ($control)
				{
					$control->label(trans('orchestra/foundation::label.email.command'));
				});

				$fieldset->control('select', 'email_queue', function ($control)
				{
					$control->label(trans('orchestra/foundation::label.email.queue'));
					$control->attributes(array('role' => 'switcher'));
					$control->options(array(
						'yes' => 'Yes',
						'no'  => 'No',
					));
				});
			});
		});
	}
}
