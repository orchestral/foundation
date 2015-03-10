<?php namespace Orchestra\Foundation\Http\Presenters;

use Orchestra\Contracts\Html\Table\Grid as TableGrid;
use Orchestra\Contracts\Html\Table\Factory as TableFactory;

class Resource extends Presenter
{
    /**
     * Construct a new Resource presenter.
     *
     * @param  \Orchestra\Contracts\Html\Table\Factory  $table
     */
    public function __construct(TableFactory $table)
    {
        $this->table = $table;
    }

    /**
     * Table View Generator for Orchestra\Resources.
     *
     * @param  array  $model
     *
     * @return \Orchestra\Contracts\Html\Table\Builder
     */
    public function table($model)
    {
        return $this->table->of('orchestra.resources: list', function (TableGrid $table) use ($model) {
            $table->with($model, false);

            $table->layout('orchestra/foundation::components.table');

            $table->column('name')
                ->escape(false)
                ->value(function ($row) {
                    $link = app('html')->link(handles("orchestra::resources/{$row->id}"), e($row->name));

                    return app('html')->create('strong', app('html')->raw($link));
                });
        });
    }
}
