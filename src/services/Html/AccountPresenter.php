<?php namespace Orchestra\Services\Html;

use Illuminate\Support\Facades\Html,
	Orchestra\Support\Facades\Form, 
	Orchestra\Support\Facades\Table;

class AccountPresenter {

	/**
	 * Form view generator for User Account.
	 *
	 * @static
	 * @access public
	 * @param  Orchestra\Model\User $model
	 * @param  string               $url
	 * @return Orchestra\Form
	 */
	public static function profileForm($model, $url)
	{
		return Form::of('orchestra.account', function ($form) use ($model, $url)
		{
			$form->with($model);
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
}