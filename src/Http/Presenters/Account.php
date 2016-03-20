<?php

namespace Orchestra\Foundation\Http\Presenters;

use Orchestra\Contracts\Html\Form\Fieldset;
use Orchestra\Contracts\Html\Form\Grid as FormGrid;
use Orchestra\Contracts\Html\Form\Factory as FormFactory;

class Account extends Presenter
{
    /**
     * Construct a new Account presenter.
     *
     * @param  \Orchestra\Contracts\Html\Form\Factory  $form
     */
    public function __construct(FormFactory $form)
    {
        $this->form = $form;
    }

    /**
     * Form view generator for User Account.
     *
     * @param  \Orchestra\Model\User  $model
     * @param  string  $url
     *
     * @return \Orchestra\Contracts\Html\Form\Builder
     */
    public function profile($model, $url)
    {
        return $this->form->of('orchestra.account', function (FormGrid $form) use ($model, $url) {
            $form->setup($this, $url, $model);
            $form->hidden('id');

            $form->fieldset(function (Fieldset $fieldset) {
                $fieldset->control('input:text', 'email')
                    ->label(trans('orchestra/foundation::label.users.email'));

                $fieldset->control('input:text', 'fullname')
                    ->label(trans('orchestra/foundation::label.users.fullname'));
            });
        });
    }

    /**
     * Form view generator for user account edit password.
     *
     * @param  \Orchestra\Model\User  $model
     *
     * @return \Orchestra\Contracts\Html\Form\Builder
     */
    public function password($model)
    {
        return $this->form->of('orchestra.account: password', function (FormGrid $form) use ($model) {
            $form->setup($this, 'orchestra::account/password', $model);
            $form->hidden('id');

            $form->fieldset(function (Fieldset $fieldset) {
                $fieldset->control('input:password', 'current_password')
                    ->label(trans('orchestra/foundation::label.account.current_password'));

                $fieldset->control('input:password', 'new_password')
                    ->label(trans('orchestra/foundation::label.account.new_password'));

                $fieldset->control('input:password', 'confirm_password')
                    ->label(trans('orchestra/foundation::label.account.confirm_password'));
            });
        });
    }
}
