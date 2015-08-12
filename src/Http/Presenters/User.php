<?php namespace Orchestra\Foundation\Http\Presenters;

use Illuminate\Contracts\Auth\Guard;
use Orchestra\Contracts\Html\Form\Fieldset;
use Orchestra\Contracts\Html\Form\Grid as FormGrid;
use Orchestra\Contracts\Html\Table\Grid as TableGrid;
use Orchestra\Contracts\Html\Form\Factory as FormFactory;
use Orchestra\Contracts\Html\Table\Builder as TableBuilder;
use Orchestra\Contracts\Html\Table\Factory as TableFactory;

class User extends Presenter
{
    /**
     * Current logged in user contract implementation.
     *
     * @var \Illuminate\Contracts\Auth\Authenticatable|null
     */
    protected $user;

    /**
     * Construct a new User presenter.
     *
     * @param  \Illuminate\Contracts\Auth\Guard  $auth
     * @param  \Orchestra\Contracts\Html\Form\Factory  $form
     * @param  \Orchestra\Contracts\Html\Table\Factory  $table
     */
    public function __construct(Guard $auth, FormFactory $form, TableFactory $table)
    {
        $this->user  = $auth->user();
        $this->form  = $form;
        $this->table = $table;
    }

    /**
     * Table View Generator for Orchestra\Model\User.
     *
     * @param  \Orchestra\Model\User  $model
     *
     * @return \Orchestra\Contracts\Html\Table\Builder
     */
    public function table($model)
    {
        return $this->table->of('orchestra.users', function (TableGrid $table) use ($model) {
            // attach Model and set pagination option to true
            $table->with($model);
            $table->sortable($this->getSortableFields());

            $table->layout('orchestra/foundation::components.table');

            $this->prependTableColumns($model, $table);

            // Add columns
            $table->column('fullname')
                ->label(trans('orchestra/foundation::label.users.fullname'))
                ->escape(false)
                ->value($this->getFullnameColumn());

            $table->column('email')
                ->label(trans('orchestra/foundation::label.users.email'));

            $this->appendTableColumns($model, $table);
        });
    }

    /**
     * Table actions View Generator for Orchestra\Model\User.
     *
     * @param  \Orchestra\Contracts\Html\Table\Builder  $table
     *
     * @return \Orchestra\Contracts\Html\Table\Builder
     */
    public function actions(TableBuilder $table)
    {
        return $table->extend(function (TableGrid $table) {
            $table->column('actions')
                ->label('')
                ->escape(false)
                ->headers(['class' => 'th-action'])
                ->attributes(function () {
                    return ['class' => 'th-action'];
                })
                ->value($this->getActionsColumn());
        });
    }

    /**
     * Append Table Columns.
     *
     * @param  \Orchestra\Model\User  $model
     * @param  \Orchestra\Contracts\Html\Table\Grid  $table
     *
     * @return void
     */
    protected function appendTableColumns($model, TableGrid $table)
    {
        //
    }

    /**
     * Prepend Table Columns.
     *
     * @param  \Orchestra\Model\User  $model
     * @param  \Orchestra\Contracts\Html\Table\Grid  $table
     *
     * @return void
     */
    protected function prependTableColumns($model, TableGrid $table)
    {
        //
    }

    /**
     * Get sortable fields option.
     *
     * @return array
     */
    protected function getSortableFields()
    {
        return [];
    }

    /**
     * Form View Generator for Orchestra\Model\User.
     *
     * @param  \Orchestra\Model\User  $model
     *
     * @return \Orchestra\Contracts\Html\Form\Builder
     */
    public function form($model)
    {
        return $this->form->of('orchestra.users', function (FormGrid $form) use ($model) {
            $form->resource($this, 'orchestra/foundation::users', $model);

            $form->hidden('id');

            $form->fieldset(function (Fieldset $fieldset) {
                $fieldset->control('input:text', 'email')
                    ->label(trans('orchestra/foundation::label.users.email'));

                $fieldset->control('input:text', 'fullname')
                    ->label(trans('orchestra/foundation::label.users.fullname'));

                $fieldset->control('input:password', 'password')
                    ->label(trans('orchestra/foundation::label.users.password'));

                $fieldset->control('select', 'roles[]')
                    ->label(trans('orchestra/foundation::label.users.roles'))
                    ->attributes(['multiple' => true])
                    ->options(function () {
                        $roles = app('orchestra.role');

                        return $roles->lists('name', 'id');
                    })
                    ->value(function ($row) {
                        // get all the user roles from objects
                        $roles = [];
                        $pivots = $row->roles()->get();

                        foreach ($pivots as $row) {
                            $roles[] = $row->id;
                        }

                        return $roles;
                    });
            });
        });
    }

    /**
     * Get actions column for table builder.
     *
     * @return callable
     */
    protected function getActionsColumn()
    {
        return function ($row) {
            $btn   = [];
            $btn[] = app('html')->link(
                handles("orchestra::users/{$row->id}/edit"),
                trans('orchestra/foundation::label.edit'),
                [
                    'class'   => 'btn btn-mini btn-warning',
                    'role'    => 'edit',
                    'data-id' => $row->id,
                ]
            );

            if (! is_null($this->user) && $this->user->id !== $row->id) {
                $btn[] = app('html')->link(
                    handles("orchestra::users/{$row->id}/delete", ['csrf' => true]),
                    trans('orchestra/foundation::label.delete'),
                    [
                        'class'   => 'btn btn-mini btn-danger',
                        'role'    => 'delete',
                        'data-id' => $row->id,
                    ]
                );
            }

            return app('html')->create(
                'div',
                app('html')->raw(implode('', $btn)),
                ['class' => 'btn-group']
            );
        };
    }

    /**
     * Get fullname column for table builder.
     *
     * @return callable
     */
    protected function getFullnameColumn()
    {
        return function ($row) {
            $roles = $row->roles;
            $value = [];

            foreach ($roles as $role) {
                $value[] = app('html')->create('span', e($role->name), [
                    'class' => 'label label-info',
                    'role'  => 'role',
                ]);
            }

            return implode('', [
                app('html')->create('strong', e($row->fullname)),
                app('html')->create('br'),
                app('html')->create('span', app('html')->raw(implode(' ', $value)), [
                    'class' => 'meta',
                ]),
            ]);
        };
    }
}
