<?php namespace Orchestra\Foundation\Services;

use Illuminate\Support\Facades\Auth, 
	Illuminate\Support\Facades\Html,
	Orchestra\Support\Facades\Form, 
	Orchestra\Support\Facades\Table,
	Orchestra\Html\Table\TableBuilder,
	Orchestra\Model\Role;

class UserPresenter {

	/**
	 * Table View Generator for Orchestra\Model\User.
	 *
	 * @static
	 * @access public
	 * @param  Orchestra\Model\User $model
	 * @return Orchestra\Table
	 */
	public static function table($model)
	{
		return Table::of('orchestra.users', function ($table) use ($model)
		{
			// attach Model and set pagination option to true
			$table->with($model, true);

			// Add columns
			$table->column('fullname', function ($column)
			{
				$column->label(trans('orchestra/foundation::label.users.fullname'));
				$column->escape(false);
				$column->value(function ($row)
				{
					$roles = $row->roles;
					$value = array();

					foreach ($roles as $role)
					{
						$value[] = Html::create('span', e($role->name), array(
							'class' => 'label label-info',
							'role'  => 'role',
						));
					}

					return implode('', array(
						Html::create('strong', e($row->fullname)),
						Html::create('br'),
						Html::create('span', Html::raw(implode(' ', $value)), array(
							'class' => 'meta',
						)),
					));
				});
			});

			$table->column('email', function ($column)
			{
				$column->label(trans('orchestra/foundation::label.users.email'));
			});
		});
	}

	/**
	 * Table actions View Generator for Orchestra\Model\User.
	 *
	 * @static
	 * @access public
	 * @param  Orchestra\Table  $model
	 * @return Orchestra\Table
	 */
	public static function actions(TableBuilder $table)
	{
		$table->extend(function ($table)
		{
			$table->column('action', function ($column)
			{
				$column->label('');
				$column->escape(false);
				$column->label_attributes(array('class' => 'th-action'));
				$column->value(function ($row)
				{
					$btn = array();
					$btn[] = Html::link(
						handles("orchestra/foundation::users/view/{$row->id}"),
						trans('orchestra/foundation::label.edit'),
						array(
							'class' => 'btn btn-mini btn-warning',
							'role'  => 'edit',
						)
					);

					if (Auth::user()->id !== $row->id)
					{
						$btn[] = Html::link(
							handles("orchestra/foundation::users/delete/{$row->id}"),
							trans('orchestra/foundation::label.delete'),
							array(
								'class' => 'btn btn-mini btn-danger',
								'role'  => 'delete',
							)
						);
					}

					return Html::create(
						'div',
						Html::raw(implode('', $btn)),
						array('class' => 'btn-group')
					);
				});
			});
		});
	}

	/**
	 * Form View Generator for Orchestra\Model\User.
	 *
	 * @static
	 * @access public
	 * @param  Orchestra\Model\User $model
	 * @return Orchestra\Form
	 */
	public static function form($model, $type = 'create')
	{
		return Form::of('orchestra.users', function ($form) use ($model)
		{
			$form->row($model);
			$form->attributes(array(
				'url' => handles("orchestra/foundation::users/view/{$model->id}"),
				'method' => 'POST',
			));

			$form->hidden('id');

			$form->fieldset(function ($fieldset)
			{
				$fieldset->control('input:text', 'email', function ($control)
				{
					$control->label(trans('orchestra/foundation::label.users.email'));
				});

				$fieldset->control('input:text', 'fullname', function($control)
				{
					$control->label(trans('orchestra/foundation::label.users.fullname'));
				});

				$fieldset->control('input:password', 'password', function($control)
				{
					$control->label(trans('orchestra/foundation::label.users.password'));
				});

				$fieldset->control('select', 'roles[]', function ($control)
				{
					$control->label(trans('orchestra/foundation::label.users.roles'));
					$control->options(Role::lists('name', 'id'));
					$control->attributes(array('multiple' => true));
					$control->value(function ($row)
					{
						// get all the user roles from objects
						$roles = array();
						foreach ($row->roles as $row) $roles[] = $row->id;
						return $roles;
					});
				});
			});
		});
	}
}