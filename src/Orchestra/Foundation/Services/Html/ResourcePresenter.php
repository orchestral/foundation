<?php namespace Orchestra\Foundation\Services\Html;

use Illuminate\Support\Facades\HTML;
use Orchestra\Support\Facades\Table;

class ResourcePresenter {

	/**
	 * Table View Generator for Orchestra\Resources.
	 *
	 * @param  array    $model
	 * @return \Orchestra\Html\Table\TableBuilder
	 */
	public static function table($model)
	{
		return Table::of('orchestra.resources: list', function ($table) use ($model)
		{
			$table->with($model, false);
			
			$table->layout('orchestra/foundation::components.table');

			$table->column('name', function ($column)
			{
				$column->escape(false);
				$column->value(function ($row)
				{
					$link = HTML::link(handles("orchestra::resources/{$row->id}"), e($row->name));
					return HTML::create('strong', HTML::raw($link));
				});
			});
		});
	}
}
