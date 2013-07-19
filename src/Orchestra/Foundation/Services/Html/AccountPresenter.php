<?php namespace Orchestra\Foundation\Services\Html;

use Illuminate\Support\Facades\HTML;
use Orchestra\Support\Facades\Form;
use Orchestra\Support\Facades\Table;

class AccountPresenter {

	/**
	 * Form view generator for User Account.
	 *
	 * @param  \Orchestra\Model\User    $model
	 * @param  string                   $url
	 * @return \Orchestra\Html\Form\FormBuilder
	 */
	public static function profileForm($model, $url)
	{
		return Form::of('orchestra.account', function ($form) use ($model, $url)
		{
			$form->with($model);
			$form->layout('orchestra/foundation::components.form');
			$form->attributes(array(
				'url'    => $url,
				'method' => 'POST',
			));

			$form->hidden('id');

			$form->fieldset(function ($fieldset)
			{
				$fieldset->control('input:text', 'email', function ($control)
				{
					$control->label(trans('orchestra/foundation::label.users.email'));
				});

				$fieldset->control('input:text', 'fullname', function ($control)
				{
					$control->label(trans('orchestra/foundation::label.users.fullname'));
				});
			});
		});
	}

	/**
	 * Form view generator for user account edit password.
	 *
	 * @param  \Orchestra\Model\User    $model
	 * @return \Orchestra\Html\Form\FormBuilder
	 */
	public static function passwordForm($model)
	{
		return Form::of('orchestra.account: password', function ($form) use ($model)
		{
			$form->with($model);
			$form->layout('orchestra/foundation::components.form');
			$form->attributes(array(
				'url'    => handles('orchestra/foundation::account/password'),
				'method' => 'POST',
			));

			$form->hidden('id');

			$form->fieldset(function ($fieldset)
			{
				$fieldset->control('input:password', 'current_password', function ($control)
				{
					$control->label(trans('orchestra/foundation::label.account.current_password'));
				});

				$fieldset->control('input:password', 'new_password', function ($control)
				{
					$control->label(trans('orchestra/foundation::label.account.new_password'));
				});

				$fieldset->control('input:password', 'confirm_password', function ($control)
				{
					$control->label(trans('orchestra/foundation::label.account.confirm_password'));
				});
			});
		});
	}
}
